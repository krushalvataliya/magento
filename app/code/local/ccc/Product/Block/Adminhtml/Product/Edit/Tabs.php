`<?php

class Ccc_Product_Block_Adminhtml_Product_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('product')->__('product Information'));
    }
    
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('product')->__('product'),
            'title' => Mage::helper('product')->__('product Information'),
            'content' => $this->getLayout()->createBlock('product/adminhtml_product_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}





    