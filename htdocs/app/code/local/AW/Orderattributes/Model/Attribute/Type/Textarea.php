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


class AW_Orderattributes_Model_Attribute_Type_Textarea extends AW_Orderattributes_Model_Attribute_TypeAbstract
{
    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Grid_Textarea
     */
    protected function _getBackendGridRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Grid_Textarea();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Form_Textarea
     */
    protected function _getBackendFormRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Form_Textarea();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_View_Textarea
     */
    protected function _getBackendViewRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_View_Textarea();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Pdf_Text
     */
    protected function _getBackendPdfRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Pdf_Text();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Frontend_Form_Textarea
     */
    protected function _getFrontendFormRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Frontend_Form_Textarea();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Frontend_View_Textarea
     */
    protected function _getFrontendViewRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Frontend_View_Textarea();
    }

    public function getValueType()
    {
        return AW_Orderattributes_Model_Resource_Value::TEXT_TYPE;
    }

    /**
     * @param mixed $value
     */
    public function validate($value)
    {
        $helper = Mage::helper('aw_orderattributes');
        $storeId = Mage::app()->getStore()->getId();
        $label = $this->getAttribute()->getLabel($storeId);
        $label = Mage::helper('core')->escapeHtml($label);
        if (strlen(trim($value)) < 1) {
            if ($this->getAttribute()->getData('validation_rules/is_required')) {
                throw new Exception($helper->__('%s is required', $label));
            }
        }
        if ($length = $this->getAttribute()->getData('validation_rules/minimum_text_length')) {
            if (!Zend_Validate::is($value, 'StringLength', array('min' => $length, 'max' => null))) {
                throw new Exception($helper->__("%s is less than '%s' symbols", $label, $length));
            }
        }
        if ($length = $this->getAttribute()->getData('validation_rules/maximum_text_length')) {
            if (!Zend_Validate::is($value, 'StringLength', array('min' => 0, 'max' => $length))) {
                throw new Exception($helper->__("%s is greater than '%s' symbols", $label, $length));
            }
        }
        return;
    }
}