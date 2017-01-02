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
 * @author     Pixlogix Team <support@pixlogix.com>
 * @copyright  Copyright (c) 2015 Pixlogix Flexibleforms (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Pixlogix_Flexibleforms_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
	public function initControllerRouters($observer)
	{
		$front = $observer->getEvent()->getFront();
		$flexibleforms = new Pixlogix_Flexibleforms_Controller_Router();
		$front->addRouter('pixlogix', $flexibleforms);
	}

        /**
	* Validate and Match Cms Page and modify request
	*
	* @param Zend_Controller_Request_Http $request
	* @return bool
	*/
	public function match(Zend_Controller_Request_Http $request)
	{
            if (!Mage::isInstalled()) {
                    Mage::app()->getFrontController()->getResponse()
                            ->setRedirect(Mage::getUrl('install'))
                            ->sendResponse();
                    exit;
            }

            $identifier = trim($request->getPathInfo(), '/');
            $condition = new Varien_Object(array(
                    'identifier' => $identifier,
                    'continue' => true
            ));

            if ($condition->getRedirectUrl()) {
                Mage::app()->getFrontController()->getResponse()
                        ->setRedirect($condition->getRedirectUrl())
                        ->sendResponse();
                $request->setDispatched(true);
                return true;
            }

            if (!$condition->getContinue()) {
                return false;
            }
            $pos = strpos($identifier,'.html');
            $url='';
            if($pos)
            {
                $url = substr($identifier,0,$pos);
            }
            $collection = Mage::getModel('flexibleforms/flexibleforms')->getCollection()
                            ->addFieldToFilter('form_url_key', array('eq' => $url ))
                            ->getFirstItem();
            if($collection->getData()){
                    $request->setModuleName('flexibleforms');
                    $request->setControllerName('index');
                    $request->setActionName('view');
                    $request->setParam('id', $collection->getFormId());
                    return true;
            }
	}
}