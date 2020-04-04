<?php

/**
 * Catalog Rule Combine Condition data model
 */
class Codesbug_Base_Model_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('base/rule_condition_combine');
    }

    public function getNewChildSelectOptions()
    {

        $addressCondition = Mage::getModel('base/rule_condition_address');
        $addressAttributes = $addressCondition->loadAttributeOptions()->getAttributeOption();
        $attributesAdd = array();
        foreach ($addressAttributes as $code => $label) {
            $attributesAdd[] = array('value' => 'base/rule_condition_address|' . $code, 'label' => $label);
        }

        $customerCondition = Mage::getModel('base/rule_condition_customer');
        $customerAttributes = $customerCondition->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($customerAttributes as $code => $label) {
            if (!$label) {
                continue;
            }
            $attributes[] = array('value' => 'base/rule_condition_customer|' . $code, 'label' => $label);
        }

        $productCondition = Mage::getModel('base/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $product_attributes = array();
        foreach ($productAttributes as $code => $label) {
            if (!$label) {
                continue;
            }
            $product_attributes[] = array('value' => 'base/rule_condition_product|' . $code, 'label' => $label);
        }
        $conditions = parent::getNewChildSelectOptions();

        $arrays = array();
        $arrays[] = array('value' => 'salesrule/rule_condition_product_found', 'label' => Mage::helper('salesrule')->__('Product attribute combination'));
        $arrays[] = array('value' => 'salesrule/rule_condition_product_subselect', 'label' => Mage::helper('salesrule')->__('Products subselection'));
        $arrays[] = array('label' => Mage::helper('salesrule')->__('Conditions combination'), 'value' => 'base/rule_condition_combine');
        $arrays[] = array('label' => Mage::helper('catalogrule')->__('Cart Attributes'), 'value' => $attributesAdd);
        $arrays[] = array('label' => Mage::helper('catalogrule')->__('User Attributes'), 'value' => $attributes);
        $arrays[] = array('label' => Mage::helper('catalogrule')->__('Product Attributes'), 'value' => $product_attributes);

        $conditions = array_merge_recursive($conditions, $arrays);
        return $conditions;
    }

    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }

}
