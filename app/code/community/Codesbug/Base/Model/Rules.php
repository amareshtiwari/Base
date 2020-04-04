<?php

class Codesbug_Base_Model_Rules extends Mage_Rule_Model_Abstract
{

    public function getConditionsInstance()
    {
        return Mage::getModel('base/rule_condition_combine');
    }
    public function getActionsInstance()
    {}

}
