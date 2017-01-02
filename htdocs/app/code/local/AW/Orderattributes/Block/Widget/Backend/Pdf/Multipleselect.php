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

class AW_Orderattributes_Block_Widget_Backend_Pdf_Multipleselect
    extends AW_Orderattributes_Block_Widget_Backend_PdfAbstract
{
    public function getLabel()
    {
        return $this->_getLabel();
    }

    public function getValue()
    {
        $labels = array();
        foreach($this->_getOptionsForSelect() as $value => $valueLabel) {
            if (in_array($value, $this->_getValue())) {
                $labels[] = $valueLabel;
            }
        }
        return $labels;
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
            $options[$key] = $value;
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