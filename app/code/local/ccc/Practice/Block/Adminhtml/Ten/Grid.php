<?php
class Ccc_Practice_Block_Adminhtml_Ten_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('PracticeAdminhtmlPracticeGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('sku');

       $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addFieldToFilter('is_user_defined', 1)
            ->getItems();

        foreach ($attributes as $attribute) {
            $attributeCodes[] = $attribute->getAttributeCode();
        }

        $unassignedAttributes = array();

        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku');


        foreach ($products as $product) {
            $productId = $product->getId();
            $sku = $product->getSku();

            foreach ($attributeCodes as $attributeCode) {
                $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
                $attributeId = $attribute->getId();
                $value = $attribute->getSource()->getOptionText($product->getData($attributeCode));

                $resource = Mage::getResourceModel('catalog/product');
                $value = $resource->getAttributeRawValue($productId, $attributeCode, Mage::app()->getStore());

                if ($value) {
                    $unassignedAttributes[] = array(
                        'product_id' => $productId,
                        'sku' => $sku,
                        'attribute_id' => $attributeId,
                        'attribute_code' => $attributeCode,
                        'value' => $value
                    );
                }
            }
        }

        // echo $collection->getSelect();
        $collection = new Varien_Data_Collection();

        foreach ($unassignedAttributes as $data) {
            $item = new Varien_Object($data);
            $collection->addItem($item);
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('category')->__('product_id'),
            'align'     => 'left',
            'index'     => 'product_id',
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('category')->__('sku'),
            'align'     => 'left',
            'index'     => 'sku',
        ));

         $this->addColumn('attribute_id', array(
            'header'    => Mage::helper('category')->__('attribute Id'),
            'align'     => 'left',
            'index'     => 'attribute_id',
        ));

        $this->addColumn('attribute_code', array(
            'header'    => Mage::helper('category')->__('attribute_code'),
            'align'     => 'left',
            'index'     => 'attribute_code',
        ));

        $this->addColumn('value', array(
            'header'    => Mage::helper('category')->__('value'),
            'align'     => 'left',
            'index'     => 'value',
        ));
        

        return parent::_prepareColumns();
    }

}