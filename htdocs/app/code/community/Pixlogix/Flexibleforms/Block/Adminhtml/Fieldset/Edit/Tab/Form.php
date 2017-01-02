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
 * Adminhtml_Fields_Edit_Tab_General block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Fieldset_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
        /**
         * To set General Tab fields on Fields Edit page
         *
         * @return Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Edit_Tab_General
         */
	protected function _prepareForm()
	{
            
		$model = Mage::getModel('flexibleforms/fieldset');
		$form = new Varien_Data_Form();
		$this->setForm($form);
                $formId= $this->getRequest()->getParam('form_id');

                $fieldset = $form->addFieldset('flexibleforms_form', array('legend' => Mage::helper('flexibleforms')->__('Fieldset Section')));
		
                $fieldset->addField('fieldset_title', 'text', array(
			'label'     => Mage::helper('flexibleforms')->__('Title'),
			'required'  => true,
			'name'      => 'fieldset_title',
		));
		
        $fieldset->addField('fieldset_position', 'text', array(
			'label'     => Mage::helper('flexibleforms')->__('Position'),
			'required'  => false,
			'name'      => 'fieldset_position',
		));

		$fieldset->addField('fieldset_class', 'text', array(
			'label'     => Mage::helper('flexibleforms')->__('Fieldset Class'),
			'required'  => false,
			'name'      => 'fieldset_class',
		));

		$fieldset->addField('fieldset_status', 'select', array(
			'label'     => Mage::helper('flexibleforms')->__('Status'),
			'required'  => false,
			'name'      => 'fieldset_status',
			'values'    => array(
				array(
					'value'	=> 1,
					'label'	=> Mage::helper('flexibleforms')->__('Enable'),
				),
				array(
					'value'	=> 2,
					'label'	=> Mage::helper('flexibleforms')->__('Disable'),
				),
			),
		));

                $form->addField('form_id', 'hidden', array(
                    'name'		=> 'form_id',
                    'value'		=> $this->getRequest()->getParam('form_id')
                ));

                Mage::dispatchEvent('flexibleforms_adminhtml_fieldset_edit_tab_information_prepare_form', array('form' => $form, 'fieldset' => $fieldset));

                if(Mage::getSingleton('adminhtml/session')->getFlexibleformsData())
                {
                    $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleformsData());
                    Mage::getSingleton('adminhtml/session')->setFlexibleformsData(null);
                } elseif(Mage::registry('fieldset')){
                    $form->setValues(Mage::registry('fieldset')->getData());
                }

                // Set default value for field
                if(!Mage::registry('fieldset')->getFieldsetId()){
                    $form->setValues(array(
                        'form_id'	=> $this->getRequest()->getParam('form_id'),
                        'position'	=> 10
                    ));
                }
                return parent::_prepareForm();
        }
}