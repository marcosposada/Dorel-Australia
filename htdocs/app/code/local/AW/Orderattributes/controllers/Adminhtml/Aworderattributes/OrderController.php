<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Orderattributes
 * @version    1.0.4
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class AW_Orderattributes_Adminhtml_Aworderattributes_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    public function indexAction()
    {
        $this->_title($this->__('Order Attributes'))->_title($this->__('Manage Orders'));
        $this->loadLayout();
        $this->_setActiveMenu('sales/aw_orderattributes');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _title($text = null, $resetIfExists = false)
    {
        if (Mage::helper('aw_orderattributes')->checkMageVersion()) {
            return parent::_title($text, $resetIfExists);
        }
        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/aw_orderattributes/manage_orders');
    }

}