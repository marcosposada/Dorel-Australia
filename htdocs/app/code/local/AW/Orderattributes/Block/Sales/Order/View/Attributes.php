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

class AW_Orderattributes_Block_Sales_Order_View_Attributes extends Mage_Adminhtml_Block_Template
{
    public $_valueData = null;

    /**
     * @return array
     */
    public function getLinkedAttributes()
    {
        $values = array();
        foreach ($this->getAttributeCollection() as $attribute) {
            $value = $this->getValueByAttributeId($attribute->getId());
            if (!is_null($value)) {
                $values[] = $attribute;
            }
        }
        return $values;
    }

    public function getAttributeCollection()
    {
        $attributeCollection = Mage::helper('aw_orderattributes/order')->getAttributeCollectionForCustomerAccount();
        switch ((int)$this->getShowInBlock()) {
            case AW_Orderattributes_Model_Source_Showinblock::BILLING_ADDRESS_BLOCK_CODE:
                $attributeCollection->addShowInBillingAddressBlockFilter();
                break;
            case AW_Orderattributes_Model_Source_Showinblock::SHIPPING_ADDRESS_BLOCK_CODE:
                $attributeCollection->addShowInShippingAddressBlockFilter();
                break;
            case AW_Orderattributes_Model_Source_Showinblock::SHIPPING_METHOD_BLOCK_CODE:
                $attributeCollection->addShowInShippingMethodBlockFilter();
                break;
            case AW_Orderattributes_Model_Source_Showinblock::PAYMENT_METHOD_BLOCK_CODE:
                $attributeCollection->addShowInPaymentMethodBlockFilter();
                break;
            case AW_Orderattributes_Model_Source_Showinblock::ORDER_REVIEW_BLOCK_CODE:
                $attributeCollection->addShowInOrderReviewBlockFilter();
                break;
            default:
                return null;
        }
        return $attributeCollection;
    }

    public function getValueByAttributeId($attributeId)
    {
        if (is_null($this->_valueData)) {
            $this->_valueData = array();
            $collection = Mage::helper('aw_orderattributes/order')->getAttributeValueCollectionForQuote($this->getOrder()->getQuoteId());
            foreach ($collection as $item) {
                $this->_valueData[$item->getData('attribute_id')] = $item->getData('value');
            }
        }
        return isset($this->_valueData[$attributeId]) ? $this->_valueData[$attributeId] : null;
    }

    public function getOrder()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order');
        } elseif (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getOrder();
        } elseif (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getOrder();
        } elseif (Mage::registry('current_creditmemo')) {
            return Mage::registry('current_creditmemo')->getOrder();
        }
        return null;
    }
}