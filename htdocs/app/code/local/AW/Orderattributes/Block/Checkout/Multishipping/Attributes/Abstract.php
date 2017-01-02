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


abstract class AW_Orderattributes_Block_Checkout_Multishipping_Attributes_Abstract extends Mage_Core_Block_Template
{
    protected $_valueData = null;

    /**
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollection()
    {
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $collection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId);
        return $collection;
    }

    public function getValueByAttributeId($attributeId)
    {
        if (is_null($this->_valueData)) {
            $this->_valueData = array();
            $collection = Mage::helper('aw_orderattributes/order')
                ->getAttributeValueCollectionForQuote(Mage::helper('checkout')->getQuote());
            foreach ($collection as $item) {
                $this->_valueData[$item->getData('attribute_id')] = $item->getData('value');
            }
        }
        return isset($this->_valueData[$attributeId])?$this->_valueData[$attributeId]:null;
    }
}