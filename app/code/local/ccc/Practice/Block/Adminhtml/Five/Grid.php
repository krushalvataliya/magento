<?php
class Ccc_Practice_Block_Adminhtml_Five_Grid extends Mage_Adminhtml_Block_Widget_Grid
{


    public function __construct()
    {
        parent::__construct();
        $this->setId('PracticeAdminhtmlPracticeGrid');
    }

    protected function _prepareCollection()
    {
       $products = Mage::getModel('catalog/product')->getCollection();
        $products->addAttributeToSelect(array('sku','media_gallery'));
        $this->setCollection($products);
        

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('practice')->__('product_id'),
            'align'     => 'left',
            'index'     => 'entity_id',
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('practice')->__('sku'),
            'align'     => 'left',
            'index'     => 'sku',
        ));

        $this->addColumn('gallary_count', array(
            'header'    => Mage::helper('practice')->__('gallary images count'),
            'align'     => 'left',
            'index'     => 'media_gallery',
            'renderer'  =>'ccc_practice_block_adminhtml_five_renderer_count'
        ));


        return parent::_prepareColumns();
    }
   
}