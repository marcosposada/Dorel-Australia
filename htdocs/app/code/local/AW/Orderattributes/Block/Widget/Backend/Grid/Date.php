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


class AW_Orderattributes_Block_Widget_Backend_Grid_Date
    extends AW_Orderattributes_Block_Widget_Backend_GridAbstract
{
    public function getColumnId()
    {
        return $this->getProperty('code') ? $this->getProperty('code') : null;
    }

    public function getColumnProperties()
    {
        return array(
            'header'                    => $this->_getLabel(),
            'index'                     => $this->getColumnId(),
            'type'                      => 'date',
            'filter_condition_callback' => array($this, 'filterCallback')
        );
    }

    public function filterCallback($collection, $column)
    {
        $condition = $column->getFilter()->getValue();
        if (is_array($condition) && array_key_exists('from', $condition)) {
            $condition['from'] = $condition['orig_from'];
        }
        if (is_array($condition) && array_key_exists('to', $condition)) {
            $condition['to'] = $condition['orig_to'];
        }
        if ($this->getColumnId() && isset($condition)) {
            $collection->addFieldToFilter($this->getColumnId() , $condition);
        }
    }
}