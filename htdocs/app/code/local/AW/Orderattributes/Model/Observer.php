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


class AW_Orderattributes_Model_Observer
{
    public function checkoutOnepageSaveBillingPostdispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $result = Mage::helper('core')->jsonDecode($controllerAction->getResponse()->getBody());
        if (isset($result['error'])) {
            return;
        }
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInBillingAddressBlockFilter()
        ;
        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        if (count($errors) > 0) {
            $result['error'] = 1;
            $result['message'] = implode(', ', $errors);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function checkoutOnepageSaveShippingPostdispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $result = Mage::helper('core')->jsonDecode($controllerAction->getResponse()->getBody());
        if (isset($result['error'])) {
            return;
        }
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInShippingAddressBlockFilter()
        ;
        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        if (count($errors) > 0) {
            $result['error'] = 1;
            $result['message'] = implode(', ', $errors);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function checkoutOnepageSaveShippingMethodPostdispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $result = Mage::helper('core')->jsonDecode($controllerAction->getResponse()->getBody());
        if (isset($result['error'])) {
            return;
        }
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInShippingMethodBlockFilter()
        ;
        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        if (count($errors) > 0) {
            $result['error'] = 1;
            $result['message'] = implode(', ', $errors);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function checkoutOnepageSavePaymentMethodPostdispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $result = Mage::helper('core')->jsonDecode($controllerAction->getResponse()->getBody());
        if (isset($result['error'])) {
            return;
        }
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInPaymentMethodBlockFilter()
        ;
        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        if (count($errors) > 0) {
            $result['error'] = 1;
            $result['message'] = implode(', ', $errors);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function checkoutOnepageSaveOrderPredispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInOrderReviewBlockFilter()
        ;
        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        //if not ALL is OK return json from action without dispatching
        if (count($errors) > 0) {
            $result['success'] = false;
            $result['error']   = true;
            $result['error_messages'] = implode(', ', $errors);
            $controllerAction->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function checkoutMultishippingOverviewPredispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $billingAddressAttributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInBillingAddressBlockFilter()
        ;
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $paymentMethodAttributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInPaymentMethodBlockFilter()
        ;

        $billingAddressErrors = $this->_validatePostForCollection($controllerAction, $billingAddressAttributeCollection);
        $paymentMethodErrors = $this->_validatePostForCollection($controllerAction, $paymentMethodAttributeCollection);
        if (count($billingAddressErrors) === 0 && count($paymentMethodErrors) === 0) {
            //if ALL is OK
            $billingAddressErrors = $this->_savePostForCollection($controllerAction, $billingAddressAttributeCollection);
            $paymentMethodErrors = $this->_savePostForCollection($controllerAction, $paymentMethodAttributeCollection);
        }

        $errors = array_merge($billingAddressErrors, $paymentMethodErrors);
        //if not ALL is OK
        if (count($errors) > 0) {
            foreach($errors as $errMsg) {
                Mage::getSingleton('checkout/session')->addError($errMsg);
            }
            $controllerAction->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $this->_redirect($controllerAction, '*/*/billing');
        }
    }

    public function checkoutMultishippingOverviewPostPredispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
            ->addShowInOrderReviewBlockFilter()
        ;

        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }

        //if not ALL is OK
        if (count($errors) > 0) {
            foreach($errors as $errMsg) {
                Mage::getSingleton('checkout/session')->addError($errMsg);
            }
            $controllerAction->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $this->_redirect($controllerAction, '*/*/billing');
        }
    }

    public function awOnestepcheckoutAjaxPlaceOrderPredispatch($observer)
    {
        $controllerAction = $observer->getControllerAction();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        /** @var AW_Orderattributes_Model_Resource_Attribute_Collection $collection */
        $attributeCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeCollectionForCheckoutByCustomerGroupId($customerGroupId)
        ;
        $billingData = $controllerAction->getRequest()->getPost('billing', array());
        if (isset($billingData['use_for_shipping'])) {
            $attributeCollection->addNotShowInShippingAddressBlockFilter();
        }
        $shippingMethod = $controllerAction->getRequest()->getPost('shipping_method', false);
        if (!$shippingMethod) {
            $attributeCollection->addNotShowInShippingMethodBlockFilter();
        }

        $errors = $this->_validatePostForCollection($controllerAction, $attributeCollection);
        if (count($errors) === 0) {
            //if ALL is OK
            $errors = $this->_savePostForCollection($controllerAction, $attributeCollection);
        }
        //if not ALL is OK return json from action without dispatching
        if (count($errors) > 0) {
            $result['success'] = false;
            $result['messages'] = implode(', ', $errors);
            $controllerAction->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * validate attributes
     * @param $controllerAction
     * @param $attributeCollection
     *
     * @return array
     */
    protected function _validatePostForCollection($controllerAction, $attributeCollection)
    {
        $errors = array();
        foreach($attributeCollection as $attribute) {
            $code = AW_Orderattributes_Model_Attribute_TypeAbstract::FRONTEND_ATTRIBUTE_CODE_PREFIX .
                $attribute->getCode();
            $param = $controllerAction->getRequest()->getParam($code, null);
            try {
                $attribute->unpackData()->getTypeModel()->validate($param);
            } catch(Exception $e) {
                $errors[$attribute->getId()] = $e->getMessage();
            }
        }
        return $errors;
    }

    /**
     * save attributes
     * @param $controllerAction
     * @param $attributeCollection
     *
     * @return AW_Orderattributes_Model_Observer
     */
    protected function _savePostForCollection($controllerAction, $attributeCollection)
    {
        $quote = $this->_getQuote();
        $attributeValueCollection = Mage::helper('aw_orderattributes/order')
            ->getAttributeValueCollectionForQuote($quote);
        $valueData = array();
        foreach ($attributeValueCollection as $item) {
            $valueData[$item->getData('attribute_id')] = $item->getId();
        }
        $errors = array();
        foreach($attributeCollection as $attribute) {
            $code = AW_Orderattributes_Model_Attribute_TypeAbstract::FRONTEND_ATTRIBUTE_CODE_PREFIX .
                $attribute->getCode();
            $param = $controllerAction->getRequest()->getParam($code, null);
            $value = Mage::getModel('aw_orderattributes/value');
            $value->setData(
                array(
                     'attribute_id' => $attribute->getId(),
                     'quote_id'     => $quote->getId(),
                     'value'        => $param,
                )
            );
            if (array_key_exists($attribute->getId(), $valueData)) {
                $value->setId($valueData[$attribute->getId()]);
            }
            $value->setValueType($attribute->getTypeModel()->getValueType());
            try {
                $value->save();
            } catch(Exception $e) {
                $errors[$attribute->getId()] = $e->getMessage();
            }
        }
        return $errors;
    }

    /**
     * Just help function
     *
     * @param $path
     * @param $arguments
     */
    private function _redirect($controllerAction, $path, $arguments=array())
    {
        return $controllerAction->getResponse()->setRedirect(Mage::getUrl($path, $arguments));
    }

    /**
     * Get current quote model
     *
     * @return Mage_Customer_Model_Customer
     */
    private function _getQuote()
    {
        return Mage::helper('checkout')->getQuote();
    }
}