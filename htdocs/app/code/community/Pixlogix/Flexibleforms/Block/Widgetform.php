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
 * Widgetform block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Widgetform extends Pixlogix_Flexibleforms_Block_Form implements Mage_Widget_Block_Interface
{
    /**
     * Set Template for Widget Data
     *
     * @return Pixlogix_Flexibleforms_Block_Widgetform
     */
    public function _construct(){
        parent::_construct();
        $this->setTemplate('flexibleforms/widgetform.phtml');
        $enabledFlexibleforms = $this->helper('flexibleforms')->enabledFlexibleforms(); //Check if flexibleforms module is enabled
    }

    /**
     * Get form data from Widget code
     *
     * @return Pixlogix_Flexibleforms_Block_Widgetform
     */
    public function getWidgetFormData()
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $arrstoreId = array($storeId);
        $formId = $this->getData('form_id');
        $collection = Mage::getModel('flexibleforms/flexibleforms')
                ->getCollection()
                ->addFieldToFilter('form_status', array('eq' => 1 ))
                ->addFieldToFilter('form_id', array('eq' => $formId ))
                ->addFieldToFilter('store_id', array('finset' => $arrstoreId ))
                ->getFirstItem();
        return $collection;
    }
}