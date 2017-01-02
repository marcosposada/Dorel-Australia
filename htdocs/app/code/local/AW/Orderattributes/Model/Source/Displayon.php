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


class AW_Orderattributes_Model_Source_Displayon
{
    const ORDER_GRID_PAGE_CODE  = 1;
    const ORDER_GRID_PAGE_LABEL = 'Order Grid';

    const PDFS_CODE  = 2;
    const PDFS_LABEL = 'Backend PDFs';

    const CUSTOMER_ACCOUNT_CODE  = 3;
    const CUSTOMER_ACCOUNT_LABEL = 'Customer Account';

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
                'value' => self::ORDER_GRID_PAGE_CODE,
                'label' => $helper->__(self::ORDER_GRID_PAGE_LABEL),
            ),
            array(
                'value' => self::PDFS_CODE,
                'label' => $helper->__(self::PDFS_LABEL),
            ),
            array(
                'value' => self::CUSTOMER_ACCOUNT_CODE,
                'label' => $helper->__(self::CUSTOMER_ACCOUNT_LABEL),
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
            self::ORDER_GRID_PAGE_CODE  => $helper->__(self::ORDER_GRID_PAGE_LABEL),
            self::PDFS_CODE             => $helper->__(self::PDFS_LABEL),
            self::CUSTOMER_ACCOUNT_CODE => $helper->__(self::CUSTOMER_ACCOUNT_LABEL),
        );
    }
}