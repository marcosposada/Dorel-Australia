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


abstract class AW_Orderattributes_Block_Widget_Backend_GridAbstract
    implements AW_Orderattributes_Block_Widget_Backend_GridInterface
{
    /**
     * @var AW_Orderattributes_Model_Attribute_TypeInterface
     */
    protected $_type = null;

    protected $_value = null;

    /**
     * @param AW_Orderattributes_Model_Attribute_TypeInterface $type
     *
     * @return AW_Orderattributes_Block_Widget_Backend_GridAbstract
     */
    public function setTypeModel(AW_Orderattributes_Model_Attribute_TypeInterface $type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return AW_Orderattributes_Model_Attribute_TypeInterface
     */
    public function getTypeModel()
    {
        return $this->_type;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProperty($key)
    {
        if (is_null($this->getTypeModel())) {
            return null;
        }
        if (is_null($this->getTypeModel()->getAttribute())) {
            return null;
        }
        return $this->getTypeModel()->getAttribute()->getData($key);
    }

    /**
     * @param mixed $value
     *
     * @return AW_Orderattributes_Block_Widget_Backend_GridAbstract
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * getter
     *
     * @return string
     */
    protected function _getLabel()
    {
        $storeId = Mage::app()->getStore()->getId();
        $label = $this->getTypeModel()->getAttribute()->getLabel($storeId);
        return Mage::helper('core')->escapeHtml($label);
    }
}