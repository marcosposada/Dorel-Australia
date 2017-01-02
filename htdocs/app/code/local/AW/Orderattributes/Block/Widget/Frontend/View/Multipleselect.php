<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Orderattributes
 * @version    1.0.4
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Orderattributes_Block_Widget_Frontend_View_Multipleselect
    extends AW_Orderattributes_Block_Widget_Frontend_ViewAbstract
{
    public function getHtml()
    {
        $value = $this->_getValue();
        if ((count($value) === 0) || (count($value) === 1 && isset($value[0]) && $value[0] === '0')) {
            return '';
        }
        $labelHtml = $this->_getLabelHtml();
        $valueHtml = $this->_getValueHtml();
        $attributeCode = $this->_getCode();
        $html = "
            <tr id=\"{$attributeCode}\">
                <td class=\"label\">{$labelHtml}</td>
                <td class=\"value\">{$valueHtml}</td>
            </tr>
        ";
        return $html;
    }

    protected function _getLabelHtml()
    {
        return "<label for=\"{$this->_getCode()}\">{$this->_getLabel()}</label>";
    }

    protected function _getValueHtml()
    {
        $labels = array();
        foreach($this->_getOptionsForSelect() as $value => $label) {
            if (in_array($value, $this->_getValue())) {
                $labels[] = $label;
            }
        }
        $valueHtml = implode('<br />', $labels);
        return "<strong>{$valueHtml}</strong>";
        return "";
    }

    private function _getOptionsForSelect()
    {
        if (is_null($this->getTypeModel())) {
            return null;
        }
        $attribute = $this->getTypeModel()->getAttribute();
        if (is_null($attribute)) {
            return null;
        }
        $storeId = Mage::app()->getStore()->getId();
        $options = Mage::helper('aw_orderattributes/options')->getOptionsForAttributeByStoreId($attribute, $storeId, false);
        foreach($options as $key => $value) {
            $options[$key] = htmlspecialchars($value);
        }
        return $options;
    }

    protected function _getValue()
    {
        $value = parent::_getValue();
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        if (!is_array($value)) {
            $value = array();
        }
        return $value;
    }
}