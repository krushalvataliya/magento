<?php
class Kv_Vendor_Model_Resource_Vendor_Address extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('vendor/vendor_address' ,'address_id');
    }
}