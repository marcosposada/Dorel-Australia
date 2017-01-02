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


class AW_Orderattributes_Model_Source_Type extends Mage_Adminhtml_Model_System_Config_Source_Yesno
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return Mage::helper('aw_orderattributes/type')->toOptionArray();
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return Mage::helper('aw_orderattributes/type')->toArray();
    }

    /**
     * @param string $code
     *
     * @return AW_Orderattributes_Model_Attribute_TypeInterface|null
     */
    public function getModelByTypeCode($code)
    {
        return Mage::helper('aw_orderattributes/type')->getModelByTypeCode($code);
    }
}