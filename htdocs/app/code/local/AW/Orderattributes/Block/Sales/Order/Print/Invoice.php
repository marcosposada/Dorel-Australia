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

class AW_Orderattributes_Block_Sales_Order_Print_Invoice extends Mage_Sales_Block_Order_Print_Invoice
{
    protected $_mockOrder = null;

    public function getOrder()
    {
        if (is_null($this->_mockOrder)) {
            $realOrderObject =  Mage::registry('current_order');
            $this->_mockOrder = Mage::getModel('aw_orderattributes/sales_order')->setRealOrder($realOrderObject);
        }
        return $this->_mockOrder;
    }

    public function getPaymentInfoHtml()
    {
        $paymentInfoHtml = parent::getPaymentInfoHtml();
        $attributesBlock = Mage::app()->getLayout()->getBlock("aw.oa.payment.method.attributes");
        if ($attributesBlock) {
            $paymentInfoHtml .= $attributesBlock->toHtml();
        }
        return $paymentInfoHtml;
    }

    /**
     * @param mixed $data
     * @param null $allowedTags
     * @return mixed|string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return $data;
    }
}