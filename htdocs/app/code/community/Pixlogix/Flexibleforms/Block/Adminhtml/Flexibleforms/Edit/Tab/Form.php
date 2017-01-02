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
 * Adminhtml_Flexibleforms_Edit_Tab_Form block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Form Information Tab on Form Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('flexibleforms_form', array('legend'=> Mage::helper('flexibleforms')->__('Form Information')));
        // To get WYSIWYG Editor
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('tab_id' => 'form_section'));
        $wysiwygConfig["files_browser_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index');
        $wysiwygConfig["directives_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["directives_url_quoted"]	= Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["widget_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index');
        $wysiwygConfig["files_browser_window_width"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width');
        $wysiwygConfig["files_browser_window_height"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height');
        $plugins = $wysiwygConfig->getData("plugins");
        $plugins[0]["options"]["url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin');
        $plugins[0]["options"]["onclick"]["subject"] = "MagentovariablePlugin.loadChooser('".Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin')."', '{{html_id}}');";
        $plugins = $wysiwygConfig->setData("plugins", $plugins);

        $fieldset->addField('form_title', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'form_title',
            'tabindex'	=> 1
        ));

        $fieldset->addField('form_url_key', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Form Url Key'),
            'required'  => false,
            'name'      => 'form_url_key',
            'after_element_html' => '<p class="note"><span>'.$this->__('Form url key to view form on frontend.').'</span></p>',
        ));

        $fieldset->addField('form_top_description', 'editor', array(
            'name'      => 'form_top_description',
            'label'     => Mage::helper('flexibleforms')->__('Form Top Description'),
            'title'     => Mage::helper('flexibleforms')->__('Form Top Description'),
            'style'     => 'height:15em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
        ));

        $fieldset->addField('form_bottom_description', 'editor', array(
            'name'      => 'form_bottom_description',
            'label'     => Mage::helper('flexibleforms')->__('Form Bottom Description'),
            'title'     => Mage::helper('flexibleforms')->__('Form Bottom Description'),
            'style'     => 'height:15em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
        ));

        $fieldset->addField('form_success_description', 'editor', array(
            'name'      => 'form_success_description',
            'label'     => Mage::helper('flexibleforms')->__('Form Successful message'),
            'title'     => Mage::helper('flexibleforms')->__('Form Successful message'),
            'style'     => 'height:15em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
        ));

        $fieldset->addField('form_fail_description', 'editor', array(
            'name'      => 'form_fail_description',
            'label'     => Mage::helper('flexibleforms')->__('Form Fail message'),
            'title'     => Mage::helper('flexibleforms')->__('Form Fail message'),
            'style'     => 'height:15em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
        ));

        $fieldset->addField('form_button_text', 'text', array(
            'label'     => Mage::helper('flexibleforms')->__('Form Button Text'),
            'name'      => 'form_button_text',
            'after_element_html' => '<p class="note"><span>Form submit button text.</spam></p>',
        ));

        $fieldset->addField('form_status', 'select', array(
            'label'     => Mage::helper('flexibleforms')->__('Status'),
            'name'      => 'form_status',
            'values'    => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('flexibleforms')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('flexibleforms')->__('Disabled'),
                ),
            ),
        ));

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