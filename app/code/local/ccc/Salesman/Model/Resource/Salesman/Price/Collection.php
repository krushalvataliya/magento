<?php
class Ccc_Salesman_Model_Resource_Salesman_Price_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('salesman/salesman_price');
    }
}