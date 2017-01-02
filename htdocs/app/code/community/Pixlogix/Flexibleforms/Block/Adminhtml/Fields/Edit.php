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
class Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	/**
	 * To prepare Layout
	 *
	 * @return Pixlogix_Flexibleforms_Block_Widgetform
	 */
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
                $tabsjs = $this->getLayout()->createBlock('core/template','fields_js',array(
                        'template'	=> 'flexibleforms/tabsjs.phtml',
                        'tabs_block'=> 'flexibleforms_fields_tabs'
                ));

                $this->getLayout()->getBlock('content')->append(
                        $tabsjs
                );
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
		$this->_controller = 'adminhtml_fields';

		$this->_updateButton('save', 'label', Mage::helper('flexibleforms')->__('Save Field'));
		$this->_updateButton('delete', 'label', Mage::helper('flexibleforms')->__('Delete Field'));

		$this->_addButton('saveandcontinue', array(
                    'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                    'onclick'	=> 'saveAndContinueEdit(\'' . $this->getSaveAndContinueUrl() . '\')',
                    'class'	=> 'save',
                    ), -100);
	}

	public function getBackUrl()
	{
		return $this->getUrl('*/flexibleforms_flexibleforms/edit', array('id' => Mage::registry('flexibleforms_data')->getId()));
	}
        public function getSaveAndContinueUrl()
        {
            return $this->getUrl('*/*/save', array(
                '_current'	=> true,
                'back'		=> 'edit',
                'active_tab'=> '{{tab_id}}'
            ));
        }

	public function getHeaderText()
	{
		if (Mage::registry('field') && Mage::registry('field')->getId()) {
			return Mage::helper('flexibleforms')->__("Edit '%s' Field (%s)", $this->htmlEscape(Mage::registry('field')->getTitle()), $this->htmlEscape($this->htmlEscape(Mage::registry('flexibleforms_data')->getFormTitle())));
		} else {
			return Mage::helper('flexibleforms')->__('Add Field (%s)', $this->htmlEscape(Mage::registry('flexibleforms_data')->getFormTitle()));
		}
	}
}