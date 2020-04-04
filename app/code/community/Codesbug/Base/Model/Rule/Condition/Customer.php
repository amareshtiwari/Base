<?php

/**
 * Catalog Rule Product Condition data model
 */
class Codesbug_Base_Model_Rule_Condition_Customer extends Mage_Rule_Model_Condition_Product_Abstract
{

    /**
     * Validate product attribute value for condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function loadAttributeOptions()
    {

        $productAttributes = Mage::getResourceSingleton('customer/customer')
            ->loadAllAttributes()
            ->getAttributesByCode();

        $attributes = array();

        foreach ($productAttributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!$attribute->getDataUsingMethod($this->_isUsedForRuleProperty)
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

    public function asHtmlRecursive()
    {

        $rule_id = Mage::registry('rule_id');
        Mage::log($rule_id);
        if (!empty($rule_id)) {
            Mage::getSingleton('core/session')->setIdRule($rule_id);
        }
        $idRule = Mage::getSingleton('core/session')->getIdRule();
        $attribute = $this->getAttribute();
        $rules_data = Mage::getModel('trello/rules')->load($idRule);
        $unserialized_array = array();
        $genderoption = array('1' => 'Male', '2' => 'Female');
        $groupoption = array('1' => 'General', '2' => 'Wholesale', '3' => 'Retailer');

        if (!empty($rule_id)) {
            $conditions = unserialize($rules_data->getConditionsSerialized());
            $custom_conditions = $this->setValueConditions($conditions);
        }

        if ($attribute == 'gender') {
            $html = $this->getTypeElementHtml();
            $html .= $this->getAttributeElementHtml();
            $html .= $this->getOperatorElementHtml();

            if (isset($custom_conditions['gender'])) {
                $html .= '<span class="rule-param"><a class="label" href="javascript:void(0)">' . $custom_conditions['gender_label'] . '</a><span class="element">';
            } else {
                $html .= '<span class="rule-param"><a class="label" href="javascript:void(0)">...</a><span class="element">';
            }
            $html .= '<select id="' . $this->getPrefix() . '__' . $this->getId() . '__value" class="' . $this->getAttribute() . '_readonly element-value-changer select" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]">';
            $html .= '<option value=""></option>';
            foreach ($genderoption as $key => $gen) {
                if (isset($custom_conditions['gender'])) {
                    if ($custom_conditions['gender'] == $key) {
                        $html .= '<option value=' . $key . ' selected>' . $gen . '</option>';
                    } else {
                        $html .= '<option value=' . $key . '>' . $gen . '</option>';
                    }
                } else {
                    $html .= '<option value=' . $key . '>' . $gen . '</option>';
                }
            }

            $html .= '</select></span></span>';
            $html .= $this->getRemoveLinkHtml();
            $html .= $this->getChooserContainerHtml();
            return $html;
        }
        if ($attribute == 'group_id') {
            $html = $this->getTypeElementHtml();
            $html .= $this->getAttributeElementHtml();
            $html .= $this->getOperatorElementHtml();
            if (isset($custom_conditions['group_id'])) {
                $html .= '<span class="rule-param"><a class="label" href="javascript:void(0)">' . $custom_conditions['group_id_label'] . '</a><span class="element">';
            } else {
                $html .= '<span class="rule-param"><a class="label" href="javascript:void(0)">...</a><span class="element">';
            }
            $html .= '<select id="' . $this->getPrefix() . '__' . $this->getId() . '__value" class="' . $this->getAttribute() . '_readonly element-value-changer select" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]">';
            foreach ($groupoption as $key => $gen) {
                if (isset($custom_conditions['group_id'])) {
                    if ($custom_conditions['group_id'] == $key) {
                        $html .= '<option value=' . $key . ' selected>' . $gen . '</option>';
                    } else {
                        $html .= '<option value=' . $key . '>' . $gen . '</option>';
                    }
                } else {
                    $html .= '<option value=' . $key . '>' . $gen . '</option>';
                }
            }
            $html .= '</select></span></span>';
            $html .= $this->getRemoveLinkHtml();
            $html .= $this->getChooserContainerHtml();
            return $html;
        }

        if ($attribute == 'created_at') {
            $html = $this->getTypeElementHtml();
            $html .= $this->getAttributeElementHtml();
            $html .= $this->getOperatorElementHtml();
            if (isset($custom_conditions['created_at'])) {
                $html .= '<span class="rule-param  custome-date"><a class="label" href="javascript:void(0)">' . $custom_conditions['created_at'] . '</a><span class="element">';
            } else {
                $html .= '<span class="rule-param  custome-date"><a class="label" href="javascript:void(0)">...</a><span class="element">';
            }

            if (isset($custom_conditions['created_at'])) {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="' . $custom_conditions['created_at'] . '" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';
            } else {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';

            }

            $html .= ' <img title="Select Date" id="' . $this->getPrefix() . '__' . $this->getId() . '__value_trig"  class="v-middle" style=""  alt="" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . '/adminhtml/default/default/images/grid-cal.gif">';
            $html .= ' <a class="rule-param-apply" href="javascript:void(0)"><img class="v-middle" title="Apply" alt="Apply" src="http://127.0.0.1/trello_magento/magento/skin/adminhtml/default/default/images/rule_component_apply.gif"></a>';
            $html .= '</span></span>';
            $html .= $this->getRemoveLinkHtml();
            $html .= $this->getChooserContainerHtml();
            return $html;
        }
        if ($attribute == 'store_id') {
            $html = $this->getTypeElementHtml();
            $html .= $this->getAttributeElementHtml();
            $html .= $this->getOperatorElementHtml();
            if (isset($custom_conditions['store_id'])) {
                $html .= '<span class="rule-param custome-date"><a class="label" href="javascript:void(0)">' . $custom_conditions['store_id'] . '</a><span class="element">';
            } else {
                $html .= '<span class="rule-param  custome-date"><a class="label" href="javascript:void(0)">...</a><span class="element">';
            }

            if (isset($custom_conditions['store_id'])) {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="' . $custom_conditions['store_id'] . '" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';
            } else {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';
            }

            $html .= ' <img title="Select Date" id="' . $this->getPrefix() . '__' . $this->getId() . '__value_trig"  class="v-middle" style=""  alt="" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . '/adminhtml/default/default/images/grid-cal.gif">';
            $html .= ' <a class="rule-param-apply" href="javascript:void(0)"><img class="v-middle" title="Apply" alt="Apply" src="http://127.0.0.1/trello_magento/magento/skin/adminhtml/default/default/images/rule_component_apply.gif"></a>';
            $html .= '</span></span>';
            $html .= $this->getRemoveLinkHtml();
            $html .= $this->getChooserContainerHtml();
            return $html;
        }
        if ($attribute == 'dob') {
            $html = $this->getTypeElementHtml();
            $html .= $this->getAttributeElementHtml();
            $html .= $this->getOperatorElementHtml();
            if (isset($custom_conditions['dob'])) {
                $html .= '<span class="rule-param  custome-date"><a class="label" href="javascript:void(0)">' . $custom_conditions['dob'] . '</a><span class="element">';
            } else {
                $html .= '<span class="rule-param  custome-date"><a class="label" href="javascript:void(0)">...</a><span class="element">';
            }

            if (isset($custom_conditions['dob'])) {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="' . $custom_conditions['dob'] . '" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';
            } else {
                $html .= '<input id="' . $this->getPrefix() . '__' . $this->getId() . '__value" title="" value="" class="' . $this->getAttribute() . '_readonly element-value-changer input-text" type="text" name="rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]"/>';
            }

            $html .= ' <img title="Select Date" id="' . $this->getPrefix() . '__' . $this->getId() . '__value_trig"  class="v-middle" style=""  alt="" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . '/adminhtml/default/default/images/grid-cal.gif">';
            $html .= ' <a class="rule-param-apply" href="javascript:void(0)"><img class="v-middle" title="Apply" alt="Apply" src="http://127.0.0.1/trello_magento/magento/skin/adminhtml/default/default/images/rule_component_apply.gif"></a>';
            $html .= '</span></span>';
            $html .= $this->getRemoveLinkHtml();
            $html .= $this->getChooserContainerHtml();
            return $html;
        }

        return parent::asHtmlRecursive();
    }

    public function setValueConditions($conditions)
    {
        $genderoption = array('1' => 'Male', '2' => 'Female');
        $groupoption = array('1' => 'General', '2' => 'Wholesale', '3' => 'Retailer');
        Mage::log($conditions);
        foreach ($conditions['conditions'] as $value) {
            $condition_type = $value['type'];
            $condition_check_cumbine = strcmp(trim($condition_type), 'trello/rule_condition_combine');
            if ($condition_check_cumbine == 0) {
                $this->setValueConditions($value);
            } elseif ($value['attribute'] == 'gender') {
                $custom_conditions['gender'] = $value['value'];
            } elseif ($value['attribute'] == 'group_id') {
                $custom_conditions['group_id'] = $value['value'];
            } elseif ($value['attribute'] == 'created_at') {
                $custom_conditions['created_at'] = $value['value'];
            } elseif ($value['attribute'] == 'store_id') {
                $custom_conditions['store_id'] = $value['value'];
            } elseif ($value['attribute'] == 'dob') {
                $custom_conditions['dob'] = $value['value'];
            }
        }
        foreach ($genderoption as $key => $value) {
            if (isset($custom_conditions['gender'])) {
                if ($custom_conditions['gender'] == $key) {
                    $custom_conditions['gender_label'] = $value;
                }
            }
        }
        foreach ($groupoption as $key => $value) {
            if (isset($custom_conditions['group_id'])) {
                if ($custom_conditions['group_id'] == $key) {
                    $custom_conditions['group_id_label'] = $value;
                }
            }
        }
        if (isset($custom_conditions)) {
            return $custom_conditions;
        }
    }
}
