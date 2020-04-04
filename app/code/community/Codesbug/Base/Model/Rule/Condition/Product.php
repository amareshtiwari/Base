<?php

/**
 * Catalog Rule Product Condition data model
 */
class Codesbug_Base_Model_Rule_Condition_Product extends Mage_Rule_Model_Condition_Product_Abstract
{

    /**
     * Validate product attribute value for condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function loadAttributeOptions()
    {

        $productAttributes = Mage::getResourceSingleton('catalog/product')
            ->loadAllAttributes()
            ->getAttributesByCode();

        $attributes = array();

        foreach ($productAttributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!$attribute->isAllowedForRuleCondition() || !$attribute->getDataUsingMethod($this->_isUsedForRuleProperty)
            ) {
                $attributes[$attribute->getAttributeCode()] = '1';
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

}
