<?php

class Kv_Eavmgmt_ImportController extends Mage_Adminhtml_Controller_Action
{
    public function importoptionsAction()
    {
        if ($_FILES['import_options']['error'] == UPLOAD_ERR_OK) {
            $csvFile = $_FILES['import_options']['tmp_name'];
            $csvData = file_get_contents($csvFile);
            $csvData = array();

            if (($handle = fopen($csvFile, 'r')) !== false) {
                // Read each line of the file
                while (($data = fgetcsv($handle)) !== false) {
                    // Convert the line into an array
                    $row = array();
                    foreach ($data as $value) {
                        $row[] = $value;
                    }
                    // Add the row to the CSV data array
                    $csvData[] = $row;
                }
                  fclose($handle);
            }

            $header = [];
            foreach ($csvData as $value)
            {
                if(!$header)
                {
                    $header = $value;
                }
                else
                {
                    $data = array_combine($header,$value);

                    $collection = Mage::getResourceModel('eav/entity_attribute_collection');
                    $collection->setCodeFilter($data['Attribute Code']);
                    $attribute = $collection->getData();

                    $collection = Mage::getModel('eav/entity_attribute_option')->getCollection();
                    $collection->getSelect()
                    ->join(
                        array('eav_attribute_option_value' => Mage::getSingleton('core/resource')->getTableName('eav_attribute_option_value')),
                        'main_table.option_id = eav_attribute_option_value.option_id',
                        array('value')
                    )
                    ->where('eav_attribute_option_value.value = ?', $data['Option Name']);
                    $existingOption = $collection->getData();

                    $optionModel = Mage::getModel('eav/entity_attribute_option');
                    if (!$existingOption) {

                        $setData = ['attribute_id' => $attribute[0]['attribute_id'],'sort_order'=>$data['Option Order']];                            
                        $optionModel->setData($setData);
                        $optionModel->save();

                        $resource = Mage::getSingleton('core/resource');
                        $connection = $resource->getConnection('core_write');
                        $tableName = $resource->getTableName('eav_attribute_option_value');

                        $data = array(
                            'option_id' => $optionModel->option_id,
                            'store_id' => 0,
                            'value' => $data['Option Name']
                        );

                        if(!$connection->insert($tableName, $data))
                        {
                            throw new Exception("option not inserted.", 1);
                            
                        }
                    }
                }
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('eavmgmt')->__('Data Imported successfully.'));
        $this->_redirect('*/adminhtml_eavmgmt/index');
    }
}
