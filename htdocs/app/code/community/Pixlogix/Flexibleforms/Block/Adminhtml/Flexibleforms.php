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
 * Adminhtml_Flexibleforms block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize Adminhtml Manage Form block
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_flexibleforms';
        $this->_blockGroup = 'flexibleforms';
        $this->_headerText = Mage::helper('flexibleforms')->__('Manage Forms');
        $this->_addButtonLabel = Mage::helper('flexibleforms')->__('Add Form');
        parent::__construct();
    }
}