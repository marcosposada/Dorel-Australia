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


class AW_Orderattributes_Model_Source_Showinblock
{
    const BILLING_ADDRESS_BLOCK_CODE   = 1;
    const BILLING_ADDRESS_BLOCK_LABEL  = 'Billing Address';

    const SHIPPING_ADDRESS_BLOCK_CODE  = 2;
    const SHIPPING_ADDRESS_BLOCK_LABEL = 'Shipping Address';

    const SHIPPING_METHOD_BLOCK_CODE  = 3;
    const SHIPPING_METHOD_BLOCK_LABEL = 'Shipping Method';

    const PAYMENT_METHOD_BLOCK_CODE  = 4;
    const PAYMENT_METHOD_BLOCK_LABEL = 'Payment Method';

    const ORDER_REVIEW_BLOCK_CODE  = 5;
    const ORDER_REVIEW_BLOCK_LABEL = 'Order Review';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('aw_orderattributes');
        return array(
            array(
                'value' => self::BILLING_ADDRESS_BLOCK_CODE,
                'label' => $helper->__(self::BILLING_ADDRESS_BLOCK_LABEL),
            ),
            array(
                'value' => self::SHIPPING_ADDRESS_BLOCK_CODE,
                'label' => $helper->__(self::SHIPPING_ADDRESS_BLOCK_LABEL),
            ),
            array(
                'value' => self::SHIPPING_METHOD_BLOCK_CODE,
                'label' => $helper->__(self::SHIPPING_METHOD_BLOCK_LABEL),
            ),
            array(
                'value' => self::PAYMENT_METHOD_BLOCK_CODE,
                'label' => $helper->__(self::PAYMENT_METHOD_BLOCK_LABEL),
            ),
            array(
                'value' => self::ORDER_REVIEW_BLOCK_CODE,
                'label' => $helper->__(self::ORDER_REVIEW_BLOCK_LABEL),
            ),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $helper = Mage::helper('aw_orderattributes');
        return array(
            self::BILLING_ADDRESS_BLOCK_CODE  => $helper->__(self::BILLING_ADDRESS_BLOCK_LABEL),
            self::SHIPPING_ADDRESS_BLOCK_CODE => $helper->__(self::SHIPPING_ADDRESS_BLOCK_LABEL),
            self::SHIPPING_METHOD_BLOCK_CODE  => $helper->__(self::SHIPPING_METHOD_BLOCK_LABEL),
            self::PAYMENT_METHOD_BLOCK_CODE   => $helper->__(self::PAYMENT_METHOD_BLOCK_LABEL),
            self::ORDER_REVIEW_BLOCK_CODE     => $helper->__(self::ORDER_REVIEW_BLOCK_LABEL),
        );
    }
}