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
 * @package    AW_Storelocator
 * @version    1.1.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Storelocator_Block_Adminhtml_Hour_Grid_Column_Renderer_Days
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $day = $this->getColumn()->getIndex();

        $store_hours = '';
        if (in_array($day, array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'))) {
            $store_hours .= $row->getData($day.'_is_open') ? 'Open': 'Closed';
            if ($row->getData($day.'_is_open')) {
                $store_hours .= ' (';
                $store_hours .= date('H:i', strtotime($row->getData($day.'_opening_times')));
                $store_hours .= ' - ';
                $store_hours .= date('H:i', strtotime($row->getData($day.'_closing_times')));
                $store_hours .= ')';
            }
        }

        return $store_hours;
    }
}
