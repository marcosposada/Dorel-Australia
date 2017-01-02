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

class AW_Orderattributes_Block_Widget_Frontend_View_Date
    extends AW_Orderattributes_Block_Widget_Frontend_ViewAbstract
{
    public function getHtml()
    {
        if (is_null($this->_getValue())) {
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
        $dateHtml = $this->_getValue();;
        if (!is_null($dateHtml)) {
            $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_FULL);
            $dateHtml = $dateHtml->toString($format);
        } else {
            return '';
        }
        return "<strong>{$dateHtml}</strong>";
    }

    protected function _getValue()
    {
        if (is_null($this->_value)) {
            $value = $this->getProperty('default_value');
            if (!is_null($value) && strlen(trim($value)) > 0) {
                try {
                    return Mage::app()->getLocale()->date($value, Zend_Date::ISO_8601);
                } catch(Exception $e) {
                    return $value;
                }
            } else {
                return null;
            }
        }
        return Mage::app()->getLocale()->date($this->_value, Zend_Date::ISO_8601);
    }
}