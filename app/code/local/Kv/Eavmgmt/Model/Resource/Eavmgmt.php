<?php

class Kv_Eavmgmt_Model_Resource_Eavmgmt extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {  
        $this->_init('eavmgmt/eavmgmt', 'eavmgmt_id');
    }  
}