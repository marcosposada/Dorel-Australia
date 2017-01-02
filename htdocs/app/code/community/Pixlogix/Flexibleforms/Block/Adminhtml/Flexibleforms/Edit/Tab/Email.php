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
 * Adminhtml_Flexibleforms_Edit_Tab_Email block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Email extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To initialize Email Settings Tab on Form Edit Tab
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Email
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('flexibleforms_form', array('legend'=>Mage::helper('flexibleforms')->__('Email Settings')));

        $fieldset->addField('enable_admin_email', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Enable Email to Admin Notification'),
            'required'  => false,
            'name'      => 'enable_admin_email',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('flexibleforms')->__('Yes'),
                ),
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('flexibleforms')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('admin_email_address', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Admin To Email Address'),
            'required'  => false,
            'name'      => 'admin_email_address',
            'note' => Mage::helper('flexibleforms')->__('Admin notification to this email address'),
        ));
		$fieldset->addField('enable_admin_email_template', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Form Specific Admin Email Template'),
            'required'  => false,
            'name'      => 'enable_admin_email_template',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('flexibleforms')->__('Yes'),
                ),
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('flexibleforms')->__('No'),
                ),
            ),
            'note' => Mage::helper('flexibleforms')->__('Enable/ Disable form specific admin email template'),
        ));

		$fieldset->addField('admin_email_template', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Admin Email Template'),
            'required'  => false,
            'name'      => 'admin_email_template',
            'values'    => Mage::helper('flexibleforms')->getAdminTransactionalEmail(),
            'note' => Mage::helper('flexibleforms')->__('Form specific email template for admin email'),
        ));

        $fieldset->addField('enable_customer_email', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Enable Email to Customer Notification'),
            'required'  => false,
            'name'      => 'enable_customer_email',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('flexibleforms')->__('Yes'),
                ),
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('flexibleforms')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('customer_reply_email', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Customer Reply from Email'),
            'required'  => false,
            'name'      => 'customer_reply_email',
            'note' => Mage::helper('flexibleforms')->__('Customer notification from this email address. <br>This Email address must contain to the same domain as the site.<br>i.e. <strong>no-reply@domain.com</strong>')
        ));
		$fieldset->addField('enable_customer_email_template', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Form Specific Customer Email Template'),
            'required'  => false,
            'name'      => 'enable_customer_email_template',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('flexibleforms')->__('Yes'),
                ),
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('flexibleforms')->__('No'),
                ),
            ),
            'note' => Mage::helper('flexibleforms')->__('Enable/ Disable form specific customer email template'),
        ));
		$fieldset->addField('customer_email_template', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Customer Email Template'),
            'required'  => false,
            'name'      => 'customer_email_template',
            'values'    => Mage::helper('flexibleforms')->getCustomerTransactionalEmail(),
            'note' 		=> Mage::helper('flexibleforms')->__('Form specific email template for customer email'),
        ));

        // Add this line just before setValues() to get selected values on dropdown
        $formValues['enable_admin_email']    = array(1);
        $formValues['enable_customer_email'] = array(1);
        if ( Mage::getSingleton('adminhtml/session')->getFlexibleformsData() )
        {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFlexibleformsData() );
            Mage::getSingleton('adminhtml/session')->setFlexibleformsData(null);
        } elseif ( Mage::registry('flexibleforms_data') ) {
            $formValues = array_merge($formValues, Mage::registry('flexibleforms_data')->getData() );
        }
        $form->setValues($formValues);

		$this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence','fields_general_dependence')
            	->addFieldMap('enable_admin_email_template', 'enable_admin_email_template_id')
				->addFieldMap('admin_email_template', 'admin_email_template_id')
				->addFieldMap('enable_customer_email_template', 'enable_customer_email_template_id')
				->addFieldMap('customer_email_template', 'customer_email_template_id')
				->addFieldDependence(
					'admin_email_template_id',
					'enable_admin_email_template_id',
					'1'
	            )
				->addFieldDependence(
					'customer_email_template_id',
					'enable_customer_email_template_id',
					'1'
	            )
		);
			
			
        return parent::_prepareForm();
    }
}