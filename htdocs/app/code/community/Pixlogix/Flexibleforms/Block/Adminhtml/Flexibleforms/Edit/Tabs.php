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
 * Adminhtml_Flexibleforms_Edit_Tabs block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * To initialize Tabs on Form Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tabs
     */
    public function __construct()
    {
            parent::__construct();
            $this->setId('flexibleforms_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(Mage::helper('flexibleforms')->__('Form Information'));
    }

    protected function _beforeToHtml()
    {
            $id= $this->getRequest()->getParam('id');
            $this->addTab('form_section', array(
                    'label'     => Mage::helper('flexibleforms')->__('Form Information'),
                    'title'     => Mage::helper('flexibleforms')->__('Form Information'),
                    'content'   => $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tab_form')->toHtml(),
                    'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'form_section')).'#',
                    'class'     => 'pages-tabs'
            ));

            $this->addTab('general_settings',array(
                    'label'	=> Mage::helper('flexibleforms')->__('General Settings'),
                    'title'	=> Mage::helper('flexibleforms')->__('General Settings'),
                    'content'	=> $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tab_general')->toHtml(),
                    'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'general_settings')).'#',
                    'class'     => 'pages-tabs',
            ));

            $this->addTab('email_settings',array(
                    'label'	=> Mage::helper('flexibleforms')->__('Email Settings'),
                    'title'	=> Mage::helper('flexibleforms')->__('Email Settings'),
                    'content'	=> $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tab_email')->toHtml(),
                    'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'email_settings')).'#',
                    'class'     => 'pages-tabs',
            ));

            if ( ($this->getRequest()->getActionName() === 'edit') && ($this->getRequest()->getParam('id')) ) {
                    $this->addTab('fieldset_section',array(
                            'label'	=> Mage::helper('flexibleforms')->__('Fieldset List'),
                            'title'	=> Mage::helper('flexibleforms')->__('Fieldset List'),
                            'url'   	=> $this->getUrl('*/*/fieldsetlist',array('_current'=>true,'active_tab' => 'fieldset_section')).'#',
                            'class' 	=> 'ajax'
                    ));
                    $this->addTab('fieldslist_section',array(
                            'label'	=> Mage::helper('flexibleforms')->__('Fields List'),
                            'title'	=> Mage::helper('flexibleforms')->__('Fields List'),
                            'url'   	=> $this->getUrl('*/*/fieldslist',array('_current'=>true,'active_tab' => 'fieldslist_section')).'#',
                            'class' 	=> 'ajax'
                    ));
            }
            return parent::_beforeToHtml();
    }
}