<?php
/**
 * Pixlogix Flexibleforms
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @copyright  Copyright (c) 2015 Pixlogix Flexibleforms (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml_Flexibleforms_Edit_Tab_General block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare General Settings Tab on Form Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_General
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('flexibleforms_form', array('legend'=>Mage::helper('flexibleforms')->__('General Settings')));
        $fieldset->addField('form_redirect_url', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Redirect Url'),
            'required'  => false,
            'name'      => 'form_redirect_url',
            'note'      => $this->__('To page redirect after successful form submission. <br />i.e. http://www.domain.com')
        ));

        $fieldset->addField('enable_captcha', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Enable Captcha'),
            'required'  => false,
            'name'      => 'enable_captcha',
            'values'    => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('flexibleforms')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('flexibleforms')->__('No'),
                ),
            ),
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('flexibleforms')->__('Store View'),
                'title' => Mage::helper('flexibleforms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
        if ( Mage::getSingleton('adminhtml/session')->getFlexibleformsData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleformsData());
            Mage::getSingleton('adminhtml/session')->setFlexibleformsData(null);
        } elseif ( Mage::registry('flexibleforms_data') ) {
            $form->setValues(Mage::registry('flexibleforms_data')->getData());
        }
        return parent::_prepareForm();
    }
}