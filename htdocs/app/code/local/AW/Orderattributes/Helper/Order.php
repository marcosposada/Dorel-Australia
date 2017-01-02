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


class AW_Orderattributes_Helper_Order extends Mage_Core_Helper_Data
{
    /**
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollectionForOrderGrid()
    {
        $collection = Mage::getModel('aw_orderattributes/attribute')->getCollection();
        $collection
            ->addIsEnabledFilter()
            ->addIsDisplayOnGridFilter()
            ->sortBySortOrder(Varien_Data_Collection::SORT_ORDER_DESC)
        ;
        return $collection;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollectionForOrderViewByAdmin()
    {
        $collection = Mage::getModel('aw_orderattributes/attribute')->getCollection();
        $collection
            ->addIsEnabledFilter()
            ->sortBySortOrder()
        ;
        return $collection;
    }

    /**
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollectionForPdf()
    {
        $collection = Mage::getModel('aw_orderattributes/attribute')->getCollection();
        $collection
            ->addIsEnabledFilter()
            ->addIsDisplayOnPdfFilter()
            ->sortBySortOrder()
        ;
        return $collection;
    }

    /**
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollectionForCustomerAccount()
    {
        $collection = Mage::getModel('aw_orderattributes/attribute')->getCollection();
        $collection
            ->addIsEnabledFilter()
            ->addIsDisplayOnCustomerAccountFilter()
            ->sortBySortOrder()
        ;
        return $collection;
    }

    /**
     * @param int $customerGroupId
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
    {
        $collection = Mage::getModel('aw_orderattributes/attribute')->getCollection();
        $collection
            ->addIsEnabledFilter()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCustomerGroupFilter($customerGroupId)
            ->sortBySortOrder()
        ;
        return $collection;
    }

    /**
     * @param Mage_Sales_Model_Quote|int $quote
     * @return object
     */
    public function getAttributeValueCollectionForQuote($quote)
    {
        if ($quote instanceof Mage_Sales_Model_Quote) {
            $quote = $quote->getId();
        } elseif (!is_numeric($quote)) {
            $quote = 0;
        }
        $collection = Mage::getModel('aw_orderattributes/value')->getCollection();
        $collection->addQuoteFilter($quote);
        return $collection;
    }
}