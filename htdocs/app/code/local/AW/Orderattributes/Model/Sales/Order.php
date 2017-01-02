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

class AW_Orderattributes_Model_Sales_Order extends Mage_Sales_Model_Order
{
    protected $_realOrder = null;
    protected $_mockShippingAddress = null;
    protected $_mockBillingAddress = null;

    public function setRealOrder($order)
    {
        $this->_realOrder = $order;
        return $this;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(
            array($this->_realOrder, $method),
            $args
        );
    }

    public function getId()
    {
        return $this->_realOrder->getId();
    }

    public function getRealOrder()
    {
        return $this->_realOrder;
    }

    public function getShippingAddress()
    {
        if (is_null($this->_mockShippingAddress)) {
            $realShippingAddress = $this->_realOrder->getShippingAddress();
            $this->_mockShippingAddress = Mage::getModel('aw_orderattributes/sales_order_address');
            $this->_mockShippingAddress->setRealAddress($realShippingAddress);
        }
        return $this->_mockShippingAddress;
    }

    public function getBillingAddress()
    {
        if (is_null($this->_mockBillingAddress)) {
            $realBillingAddress = $this->_realOrder->getBillingAddress();
            $this->_mockBillingAddress = Mage::getModel('aw_orderattributes/sales_order_address');
            $this->_mockBillingAddress->setRealAddress($realBillingAddress);
        }
        return $this->_mockBillingAddress;
    }

    public function getShippingDescription()
    {
        $shippingDescription = $this->_realOrder->getShippingDescription();
        $attributesBlock = Mage::app()->getLayout()->getBlock("aw.oa.shipping.method.attributes");
        if ($attributesBlock) {
            $shippingDescription .= $attributesBlock->toHtml();
        }
        return $shippingDescription;
    }

    public function getPayment()
    {
        return $this->_realOrder->getPayment();
    }

    public function hasInvoices()
    {
        return $this->_realOrder->getInvoiceCollection()->count();
    }

    public function hasShipments()
    {
        return $this->_realOrder->getShipmentsCollection()->count();
    }

    public function hasCreditmemos()
    {
        return $this->_realOrder->getCreditmemosCollection()->count();
    }
}
