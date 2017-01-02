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
 * @package    AW_Storelocator
 * @version    1.1.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Storelocator_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{

    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $awStorelocator = new AW_Storelocator_Controller_Router();
        $front->addRouter('aw_storelocator', $awStorelocator);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        if ($this->_combineRequest($request)) {
            return true;
        }
        return false;
    }

    private function _combineRequest($request)
    {
        $identifier = $request->getPathInfo();
        $urlKey = Mage::helper('aw_storelocator/config')->getUrlKey(Mage::app()->getStore()->getId());
        if (!preg_match("#{$urlKey}(/$|$)#isu", $identifier)) {
            return false;
        }

        $request->setModuleName('aw_storelocator')
            ->setControllerName('location')
            ->setActionName('index')
        ;
        return true;
    }

}
