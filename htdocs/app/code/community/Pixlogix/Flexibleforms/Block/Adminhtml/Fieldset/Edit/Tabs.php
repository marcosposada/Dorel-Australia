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
 * Adminhtml_Fields_Edit_Tabs block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Fieldset_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * To initialize Field Information Tab on Fields Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Edit_Tabs
     */
    public function __construct()
    {
            parent::__construct();
            $this->setId('flexibleforms_fieldset_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(Mage::helper('flexibleforms')->__('Fieldset Information'));
    }

    protected function _beforeToHtml()
    {
            $this->addTab('fieldset_section', array(
                    'label'  => Mage::helper('flexibleforms')->__('Fieldset Section'),
                    'title'  => Mage::helper('flexibleforms')->__('Fieldset Section'),
                    'content'=> $this->getLayout()->createBlock('flexibleforms/adminhtml_fieldset_edit_tab_form')->toHtml(),
            ));
            return parent::_beforeToHtml();
    }
}