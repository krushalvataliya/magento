<?php
class Kv_Vendor_Model_Mysql4_Vendor_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {  
        $this->_init('vendor/Vendor');
    }  
}