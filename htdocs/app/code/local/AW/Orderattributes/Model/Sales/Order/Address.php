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

class AW_Orderattributes_Model_Sales_Order_Address extends Varien_Object
{
    protected $_realAddress = null;

    public function setRealAddress($address)
    {
        $this->_realAddress = $address;
        return $this;
    }

    public function format($type)
    {
        $attributesBlock = Mage::app()->getLayout()->getBlock("aw.oa.{$this->_realAddress->getData('address_type')}.address.attributes");
        $addressHtml = $this->_realAddress->format($type);
        if ($attributesBlock) {
            $addressHtml .= $attributesBlock->toHtml();
        }
        return $addressHtml;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(
            array($this->_realAddress, $method),
            $args
        );
    }
}