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


class AW_Orderattributes_Model_Resource_Option_Value_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('aw_orderattributes/option_value');
    }

    /**
     * @param $attribute AW_Orderattributes_Model_Option|int
     *
     * @return AW_Orderattributes_Model_Resource_Option_Value_Collection
     */
    public function addOptionFilter($option)
    {
        if ($option instanceof AW_Orderattributes_Model_Option) {
            $optionId = $option->getId();
        } elseif(is_numeric($option)) {
            $optionId = $option;
        } else {
            return $this;
        }
        $this->addFieldToFilter('option_id', $optionId);
        return $this;
    }
}