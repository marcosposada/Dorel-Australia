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


class AW_Storelocator_Block_Adminhtml_Hour_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('aw_storelocator_hour_tabs');
        $this->setDestElementId('edit_form');       
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'general_section',
            array(
                 'label'   => $this->__('General'),
                 'title'   => $this->__('General'),
                 'content' => $this->getLayout()->createBlock('aw_storelocator/adminhtml_hour_edit_tab_general')->toHtml()
            )
        );

        $this->addTab(
            'opening_hours_section',
            array(
                'label'   => $this->__('Schedule'),
                'title'   => $this->__('Schedule'),
                'content' => $this->getLayout()->createBlock('aw_storelocator/adminhtml_hour_edit_tab_schedule')->toHtml()
            )
        );

        $this->addTab(
            'location_section',
            array(
                'label'   => $this->__('Store Locations'),
                'title'   => $this->__('Store Locations'),
                'url'       => $this->getUrl('*/*/location', array('_current' => true)),
                'class'     => 'ajax'
            )
        );

        return parent::_beforeToHtml();
    }
}
