<?php

abstract class Codesbug_Base_Model_Abstract extends Varien_Object
{

    protected $_code = false;
    protected $_section = false;
    protected $_enablelog = false;

    public function __construct()
    {
        if (!$this->_code) {
            Mage::throwException($this->__('Code is not defined in extending class.'));
        } else {
            $this->_enablelog = Mage::getStoreConfig($this->_section . '/' . $this->_code . '/log');
        }
    }

    public function createlogFile($dir, $filename, $value)
    {
        if ($this->_enablelog) {
            if (!file_exists(Mage::getBaseDir('var') . DS . 'log' . DS . $dir)) {
                mkdir(Mage::getBaseDir('var') . DS . 'log' . DS . $dir, 0777, true);
            }
            Mage::log($value, null, $dir . DS . $filename . '.log', true);
        }
    }

}
