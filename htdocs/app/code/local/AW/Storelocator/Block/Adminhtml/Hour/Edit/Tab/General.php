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


class AW_Storelocator_Block_Adminhtml_Hour_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $hour = Mage::registry('aw_storelocator_hour');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('hour_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $this->__('General Information')));

        $isNew = !$hour->getHourId() ? true : false;

        $fieldset->addField(
            'title', 'text',
            array(
                 'label' => $this->__('Title'),
                 'title' => $this->__('Title'),
                 'name'  => 'title',
                 'class'     => 'required-entry',
                 'required'  => true,
            )
        );

        $fieldset->addField(
            'status', 'select',
            array(
                'label'   => $this->__('Enable'),
                'title'   => $this->__('Enable'),
                'name'    => 'status',
                'values' => array(
                    '1' => $this->__('Yes'),
                    '0' => $this->__('No'),
                ),
            )
        );

        if(!$isNew) {
            $fieldset->addField('hour_id',
                'hidden',
                array(
                    'name'	=> 'hour_id',
                    'id'	=> 'hour_id'
                )
            );
        }

        $form->setValues($hour->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
