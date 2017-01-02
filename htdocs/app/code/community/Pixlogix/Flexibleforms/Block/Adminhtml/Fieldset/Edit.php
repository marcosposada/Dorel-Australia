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
 * Adminhtml_Fields_Edit block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Fieldset_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * To prepare Layout
     *
     * @return Pixlogix_Flexibleforms_Block_Widgetform
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    /**
     * Set initialize Fields Edit Form
     *
     * @return Pixlogix_Flexibleforms_Block_Widgetform
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'flexibleforms';
        $this->_controller = 'adminhtml_fieldset';

        $this->_updateButton('save', 'label', Mage::helper('flexibleforms')->__('Save Fieldset'));
        $this->_updateButton('delete', 'label', Mage::helper('flexibleforms')->__('Delete Fieldset'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/flexibleforms_flexibleforms/edit', array('id' => Mage::registry('flexibleforms_data')->getId(),'active_tab' => 'fieldset_section' ));
    }

    public function getHeaderText()
    {
        if (Mage::registry('fieldset') && Mage::registry('fieldset')->getId()) {
            return Mage::helper('flexibleforms')->__("Edit '%s' Fieldset (%s)", $this->htmlEscape(Mage::registry('fieldset')->getFieldsetTitle()), $this->htmlEscape($this->htmlEscape(Mage::registry('flexibleforms_data')->getFormTitle())));
        } else {
            return Mage::helper('flexibleforms')->__('Add Fieldset (%s)', $this->htmlEscape(Mage::registry('flexibleforms_data')->getFormTitle()));
        }
    }
}