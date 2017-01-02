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


class AW_Orderattributes_Model_Attribute_Type_Dropdown extends AW_Orderattributes_Model_Attribute_TypeAbstract
{
    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Grid_Dropdown
     */
    protected function _getBackendGridRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Grid_Dropdown();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Form_Dropdown
     */
    protected function _getBackendFormRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Form_Dropdown();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_View_Dropdown
     */
    protected function _getBackendViewRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_View_Dropdown();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Backend_Pdf_Dropdown
     */
    protected function _getBackendPdfRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Backend_Pdf_Dropdown();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Frontend_Form_Dropdown
     */
    protected function _getFrontendFormRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Frontend_Form_Dropdown();
    }

    /**
     * @return AW_Orderattributes_Block_Widget_Frontend_View_Dropdown
     */
    protected function _getFrontendViewRenderer()
    {
        return new AW_Orderattributes_Block_Widget_Frontend_View_Dropdown();
    }

    public function getValueType()
    {
        return AW_Orderattributes_Model_Resource_Value::INT_TYPE;
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
        if (strlen(trim($value)) < 1 || $value == 0) {
            if ($this->getAttribute()->getData('validation_rules/is_required')) {
                throw new Exception($helper->__('%s is required', $label));
            }
        } else {
            $options = $this->getAttribute()->getStoreOptions();
            if (!array_key_exists($value, $options)) {
                throw new Exception($helper->__('%s has incorrect selected option', $label));
            }
        }
        return;
    }
}