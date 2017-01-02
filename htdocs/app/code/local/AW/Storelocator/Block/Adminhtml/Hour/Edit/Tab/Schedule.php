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


class AW_Storelocator_Block_Adminhtml_Hour_Edit_Tab_Schedule extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $hour = Mage::registry('aw_storelocator_hour');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('hour_');

        foreach (AW_Storelocator_Helper_Config::$WEEK_DAYS as $day) {
            $this->_addDayFieldset($day, $form);
        }

        $form->setValues($hour->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _addDayFieldset ($day, $form)
    {
        $fieldsetMonday = $form->addFieldset($day.'_fieldset', array('legend' => $this->__(ucfirst($day))));
        $fieldsetMonday->addType('hours', 'AW_Storelocator_Block_Adminhtml_Hour_Edit_Form_Renderer_Hours');

        $fieldsetMonday->addField(
            $day.'_is_open', 'select',
            array(
                'label'   => $this->__('Is Open'),
                'title'   => $this->__('Is Open'),
                'name'    => $day.'_is_open',
                'options' => array(
                    '1' => $this->__('Yes'),
                    '0' => $this->__('No'),
                ),
            )
        );

        $fieldsetMonday->addField(
            $day.'_opening_times', 'hours',
            array(
                'label'   => $this->__('Opening Time'),
                'title'   => $this->__('Opening Time'),
                'name'    => $day.'_opening_times',
            )
        );

        $fieldsetMonday->addField(
            $day.'_closing_times', 'hours',
            array(
                'label'   => $this->__('Closing Time'),
                'title'   => $this->__('Closing Time'),
                'name'    => $day.'_closing_times',
            )
        );

        return $form;
    }
}
