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
class Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To set General Tab fields on Fields Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Edit_Tab_General
     */
    protected function _prepareForm()
    {
        $model = Mage::getModel('flexibleforms/fields');
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $formId = $this->getRequest()->getParam('form_id');
        $fieldset = $form->addFieldset('flexibleforms_form', array('legend' => Mage::helper('flexibleforms')->__('General Settings')));

        $fieldset->addField('title','text',array(
            'label'     => Mage::helper('flexibleforms')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title'
        ));

        $type = $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Field Type'),
            'title'     => Mage::helper('flexibleforms')->__('Field Type'),
            'name'      => 'type',
            'required'  => false,
            'options'   => $model->getFieldTypesOptions(),
            'note'      => Mage::helper('flexibleforms')->__('Select <strong>email</strong> Field Type = "Email" for customer email notification.'),
        ));
		//to enter hidden field value
		$field_value = $fieldset->addField('field_value', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Field Value'),
            'title'     => Mage::helper('flexibleforms')->__('Field Value'),
            'name'      => 'field_value',
            'required'  => false
        ));

        $arrayFieldsetList = Mage::getModel('flexibleforms/fieldset')->getFieldsetOption($formId);
        if($arrayFieldsetList){
            $arrFieldsetOption = array('0' => '-- Select Fieldset --') + $arrayFieldsetList;
        }else{
            $arrFieldsetOption = array('0' => '-- Select Fieldset --');
        }

        $fieldset_id = $fieldset->addField('fieldset_id', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Fieldset'),
            'title'     => Mage::helper('flexibleforms')->__('Fieldset'),
            'name'      => 'fieldset_id',
            'required'  => false,
            'options'   => $arrFieldsetOption,
        ));

        $pre_define_value = $fieldset->addField('pre_define_var', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Pre Define Variable'),
            'title'     => Mage::helper('flexibleforms')->__('Pre Define Variable'),
            'name'      => 'pre_define_var',
            'required'  => false,
            'note'      => Mage::helper('flexibleforms')->__("To pre fill register user data in field use following shortcode<br/>{{firstname}}=user first name<br/>{{middlename}}=user middle name<br>{{lastname}}=user last name<br>{{email}} = user email<br/>{{company}} = user billing company name <br/>{{telephone}} = user billing telephone<br/>{{fax}} = user billing fax <br/>{{street}} = user billing street<br/>{{street-2}} user billing street 2<br/>{{city}} = user billing city <br/> {{country}} = user billing country <br/> {{state}} = user billing state<br/>To use user shipping record, add shipping as prefix in above mention short code. i.e. For user shipping company {{shipping-company}}"),
        ));

        $options = $fieldset->addField('value_options','textarea',array(
            'label'     => Mage::helper('flexibleforms')->__('Options'),
            'required'  => false,
            'name'      => 'value[options]',
            'note'      => Mage::helper('flexibleforms')->__("&bull; Add multiple options separated by new line<br>&bull; Add <i><strong>Option {{value=''}}</strong></i> to blank option value</i><br>&bull; Add <i><strong>Option {{selected}}</strong></i> for default selected option<br>&bull; Add <i><strong>Option {{value='any text'}}</strong></i> to replace option value"),
        ));

        $options_multi = $fieldset->addField('value_options_multi','textarea',array(
            'label'     => Mage::helper('flexibleforms')->__('Options'),
            'required'  => false,
            'name'      => 'value[options_multi]',
            'note'      => Mage::helper('flexibleforms')->__("&bull; Add multiple options separated by new line<br>&bull; Add <i><strong>Option {{selected}}</strong></i> for default selected option<br>&bull; Add <i><strong>Option {{value='any text'}}</strong></i> to replace option value"),
        ));

        $options_checkbox = $fieldset->addField('value_options_checkbox','textarea',array(
            'label'     => Mage::helper('flexibleforms')->__('Options'),
            'required'  => false,
            'name'      => 'value[options_checkbox]',
            'note'      => Mage::helper('flexibleforms')->__("&bull; Add multiple options separated by new line<br>&bull; Add <i><strong>Option {{selected}}</strong></i> for default selected option<br>&bull; Add <i><strong>Option {{value='any text'}}</strong></i> to replace option value"),
        ));

        $options_radio = $fieldset->addField('value_options_radio','textarea',array(
            'label'     => Mage::helper('flexibleforms')->__('Options'),
            'required'  => false,
            'name'      => 'value[options_radio]',
            'note'      => Mage::helper('flexibleforms')->__("&bull; Add multiple options separated by new line<br>&bull; Add <i><strong>Option {{selected}}</strong></i> for default selected option<br>&bull; Add <i><strong>Option {{value='any text'}}</strong></i> to replace option value"),
        ));

        $options_terms = $fieldset->addField('value_options_terms','textarea',array(
            'label'     => Mage::helper('flexibleforms')->__('Terms text'),
            'required'  => false,
            'name'      => 'value[options_terms]',
            'note'      => Mage::helper('flexibleforms')->__("&bull; Use below example text. i.e. <br><strong>".htmlentities("I agree to the <a href='#' target='_blank'>Terms and Conditions</a> of the website.")."</strong><br> &bull; Add <i><strong>Text {{selected}}</strong></i> for default selected checkbox."),
        ));

        $required = $fieldset->addField('required', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Required'),
            'title'     => Mage::helper('flexibleforms')->__('Required'),
            'name'      => 'required',
            'required'  => false,
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
        
        $fieldset->addField('custom_validation', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Custom error message'),
            'title'     => Mage::helper('flexibleforms')->__('Custom error message'),
            'name'      => 'custom_validation',
            'required'  => false
        ));

        $allowed_max_size = $fieldset->addField('allowed_max_size', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Allowed Max Size'),
            'title'     => Mage::helper('flexibleforms')->__('Allowed Max Size'),
            'name'      => 'allowed_max_size',
            'required'  => false,
            'note'      => Mage::helper('flexibleforms')->__("Specify max file size allowed to upload.<br/>File size specify in kb i.e 1mb = 1024kb, Example 1024"),
        ));

        $allowed_ext = $fieldset->addField('allowed_ext', 'textarea', array(
            'label'     => Mage::helper('flexibleforms')->__('Allowed File Extension(s)'),
            'title'     => Mage::helper('flexibleforms')->__('Allowed File Extension(s)'),
            'name'      => 'allowed_ext',
            'required'  => false,
            'note'      => Mage::helper('flexibleforms')->__("Add file extension(s) seperated by newline<br>i.e.<br/>pdf<br/>doc"),
        ));
        $fieldset->addField('layout','select',array(
            'label'	=> Mage::helper('flexibleforms')->__('Layout'),
            'required'	=> false,
            'name'	=> 'layout',
            'values'    => array(
                    array(
                        'value'	=> 1,
                        'label'	=> Mage::helper('flexibleforms')->__('1 Column'),
                    ),
                    array(
                        'value'	=> 2,
                        'label'	=> Mage::helper('flexibleforms')->__('1 Column With Wide'),
                    ),
                    array(
                        'value'	=> 3,
                        'label'	=> Mage::helper('flexibleforms')->__('2 Column'),
                    ),
                    
                ),
            'note'	=> Mage::helper('flexibleforms')->__('Frontend field apprearance.'),
        ));

        $fieldset->addField('position','text',array(
            'label'	=> Mage::helper('flexibleforms')->__('Position'),
            'required'	=> true,
            'name'	=> 'position',
            'note'	=> Mage::helper('flexibleforms')->__('Frontend field position.'),
        ));
        
        $fieldset->addField('form_star_max_value','text',array(
            'label'	=> Mage::helper('flexibleforms')->__('Total No. of Star Display'),
            'required'	=> false,
            'name'	=> 'form_star_max_value',
            'note'	=> Mage::helper('flexibleforms')->__('If not inserted any value then it will set 5 value by default. Maximum star value limit is 10.'),
        ));
        $fieldset->addField('form_star_default_value','text',array(
            'label'	=> Mage::helper('flexibleforms')->__('No. of stars selected by default'),
            'required'	=> false,
            'name'	=> 'form_star_default_value',
            'note'	=> Mage::helper('flexibleforms')->__('If not inserted any value then it will set 0 value by default.'),
        ));

	$fieldset->addField('tooltip','text',array(
            'label'	=> Mage::helper('flexibleforms')->__('Tooltip'),
            'name'	=> 'tooltip',
            'note'	=> Mage::helper('flexibleforms')->__('Frontend label tooltip.'),
        ));

        $fieldset->addField('field_note','textarea',array(
            'label'	=> Mage::helper('flexibleforms')->__('Note'),
            'name'	=> 'field_note',
            'note'	=> Mage::helper('flexibleforms')->__('To display note after field.'),
            'style'	=> 'height:80px',
        ));

        $fieldset->addField('field_class','text',array(
            'label'	=> Mage::helper('flexibleforms')->__('Field Css Class'),
            'name'	=> 'field_class',
            'note'	=> Mage::helper('flexibleforms')->__('Forntend field css class'),
        ));

        $fieldset->addField('field_title_to_email', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Field value to email subject'),
            'title'     => Mage::helper('flexibleforms')->__('Field value to email subject'),
            'name'      => 'field_title_to_email',
            'note'      => Mage::helper('flexibleforms')->__('This field value will be a subject in email'),
            'required'  => false,
                'values'    => array(
                    array(
                        'value'	=> 1,
                        'label'	=> Mage::helper('flexibleforms')->__('Yes'),
                    ),
                    array(
                        'value'	=> 0,
                        'label'	=> Mage::helper('flexibleforms')->__('No'),
                    ),
                ),
        ));

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Status'),
            'title'     => Mage::helper('flexibleforms')->__('Status'),
            'name'      => 'status',
            'required'  => true,
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
            'name'  => 'form_id',
            'value' => $this->getRequest()->getParam('form_id'),
        ));

        Mage::dispatchEvent('flexibleforms_adminhtml_fields_edit_tab_information_prepare_form', array('form' => $form, 'fieldset' => $fieldset));

        if(Mage::getSingleton('adminhtml/session')->getFlexibleformsData())
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleformsData());
            Mage::getSingleton('adminhtml/session')->setFlexibleformsData(null);
        } elseif(Mage::registry('field')){
            $form->setValues(Mage::registry('field')->getData());
        }

        // Set default value for field
        if(!Mage::registry('field')->getId()){
            $form->setValues(array(
                'form_id'   => $this->getRequest()->getParam('form_id'),
                'position'  => 10
            ));
        }

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence','fields_general_dependence')
            ->addFieldMap('type', 'type_id')
			->addFieldMap('field_value', 'field_value_id')
            ->addFieldMap('value_options', 'value_options_id')
            ->addFieldMap('value_options_multi', 'value_options_multi_id')
            ->addFieldMap('value_options_radio', 'value_options_radio_id')
            ->addFieldMap('value_options_checkbox', 'value_options_checkbox_id')
            ->addFieldMap('value_options_terms', 'value_options_terms_id')
            ->addFieldMap('field_title_to_email', 'field_title_to_email_id')
            ->addFieldMap('pre_define_var', 'pre_define_var_id')
            ->addFieldMap('allowed_max_size', 'allowed_max_size_id')
            ->addFieldMap('allowed_ext', 'allowed_ext_id')
            ->addFieldMap('form_star_max_value', 'form_star_max_value_id')
            ->addFieldMap('form_star_default_value', 'form_star_default_value_id')
            ->addFieldMap('required', 'required_id')
            ->addFieldMap('custom_validation', 'custom_validation_id')
            ->addFieldDependence(
                'value_options_id',
                'type_id',
                'select'
            )
            ->addFieldDependence(
                'value_options_multi_id',
                'type_id',
                'multiselect'
            )
            ->addFieldDependence(
                'value_options_radio_id',
                'type_id',
                'radio'
            )
            ->addFieldDependence(
                'value_options_checkbox_id',
                'type_id',
                'checkbox'
            )
            ->addFieldDependence(
                'value_options_terms_id',
                'type_id',
                'terms'
            )
            ->addFieldDependence(
                'field_title_to_email_id',
                'type_id',
                'text'
            )
            ->addFieldDependence(
                'pre_define_var_id',
                'type_id',
                array('text','textarea','email','number','url')
            )
            ->addFieldDependence(
                'allowed_max_size_id',
                'type_id',
                array('file','image')
            )
            ->addFieldDependence(
                'allowed_ext_id',
                'type_id',
                array('file','image')
            )
            ->addFieldDependence(
                'form_star_max_value_id',
                'type_id',
                'star'
            )
            ->addFieldDependence(
                'form_star_default_value_id',
                'type_id',
                'star'
            )
			->addFieldDependence(
                'field_value_id',
                'type_id',
                'hidden'
            )
            ->addFieldDependence(
                'custom_validation_id',
                'required_id',
                '1'
            )
        );
        return parent::_prepareForm();
    }
}