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
 * Flexibleforms data helper
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Enable/Disable Flexibleforms
     */
    public function enabledFlexibleforms()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/enable');
    }

    /**
     * Enable/Disable jQuery library file
     */
    public function enabledjQuery()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/enable_jquery');
    }

    /**
     * Enable/Disable Top Link
     */
    public function enabledTopLink()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/enable_top_link');
    }

    /**
     * Enable/Disable Footer Link
     */
    public function enabledFooterLink()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/enable_footer_link');
    }
    /**
     * Enable/Disable Flexibleforms jquery library
     */
    public function enabledJqueryLibrary()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/enable_jquery');
    }

    /**
     * Enable/Disable Flexibleforms for spacific store
     */
    public function enabledFormForStore($formId)
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $formModel = Mage::getModel('flexibleforms/flexibleforms');
        $formModel = $formModel->getCollection() 
                      ->addFieldToFilter('form_id', array('eq'=>$formId))
                      ->addFieldToFilter('form_status', array('eq'=>1))
                      ->getFirstItem();
        $arrStoreId = explode(',',$formModel->getStoreId());
        if(in_array('0',$arrStoreId)){
            return true;
            exit;
        }
        if(in_array($storeId,$arrStoreId)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * To check if admin email is enable or not
     */
    public function enableAdminEmail()
    {
        return Mage::getStoreConfig('flexibleforms_options/admin_email_configuration/enable_to_admin');
    }

    /**
     * Allow to only Logged In users or not
     */
    public function allowToLoggedInUsers()
    {
        return Mage::getStoreConfig('flexibleforms_options/general/loggedin_users');
    }

    /**
     * Get Admin Email from configration page
     */
    public function getAdminConfigEmail()
    {
        return Mage::getStoreConfig('flexibleforms_options/admin_email_configuration/admin_email_address');
    }
    /**
     * Get Admin Email from configration page
     */
    public function getAdminReplyToEmail()
    {
        return Mage::getStoreConfig('flexibleforms_options/admin_email_configuration/admin_from_email_address');
    }

    /**
     * Get Admin Name from configration page
     */
    public function getAdminConfigName()
    {
        return Mage::getStoreConfig('flexibleforms_options/admin_email_configuration/admin_email_name');
    }

    public function getCustomerSubject()
    {
        return Mage::getStoreConfig('flexibleforms_options/customer_email_configuration/customer_email_subject');
    }
    /**
     * To get Fields Model
     */
    public function getFieldsModel()
    {
        return Mage::getModel('flexibleform/fields');
    }

    function getFieldValue($field_id)
    {

        if(isset($field_id) && isset($_SESSION['options'][$field_id]))
        {
                
            $var = $_SESSION['options'][$field_id]; 
            unset($_SESSION['options'][$field_id]);
            return $var;
        }
        else
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn())
            {
                //Retrive user detail
                $customer_data=Mage::getSingleton('customer/session')->getCustomer();
                $fieldModel = Mage::getModel('flexibleforms/fields');
                $field_data = $fieldModel->getCollection() 
                                ->addFieldToFilter('field_id', array('eq'=>$field_id))
                                ->addFieldToFilter('status', array('eq'=>1))
                                ->getFirstItem();

                //Return pre define value if exists
                $predefine = $field_data->getPreDefineVar();
                $predefine_fieldvalue = $fieldModel->getPreDefineFieldValue($field_data->getPreDefineVar());
                $value = (!empty($predefine)) ? $predefine_fieldvalue : '';
                return $value;
            }
        }
    }
	/**
	 * Enable/Disable Product Inquiry form id
    */
    public function enabledProductInquiry()
    {
		$enabled = Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/enable_product_inquiry');
		
		$productInquiryFormId = $this->getProductInquiryFormId();

		if($productInquiryFormId && $enabled)
		{
			return true;		
		}
		else
		{
			return false;
		}
    }
	/**
	 * Get Inquiry form id
    */
    public function getProductInquiryFormId()
    {
        return Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/flexibleforms_list');
    }
	/**
	*	To get product inquiry form tab title
	*/
	public function getInquiryFormSectionTitle()
	{
		return Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/product_inquiry_section_title');
	}
	
	/**
	*	To get is this form is product inquiry form or not
	*/
	public function isProductInquiryForm($formId)
	{
		if(Mage::helper('flexibleforms')->getProductInquiryFormId() == $formId)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function getAdminTransactionalEmail()
	{
		$arr=array();
		$arr[0]['value'] = 'flexibleforms_options_admin_email_configuration_admin_email_template';
		$arr[0]['label'] = $this->__('Flexibleforms Admin Email (Default Template from Locale)');
		$emailCollection = Mage::getModel('core/email_template')->getCollection();
		$i=1;
		foreach($emailCollection as $email)
		{
			$arr[$i]['value'] = $email->getTemplateId();
			$arr[$i]['label'] = $this->__($email->getTemplateCode());
			$i++;
		}
		return $arr;

	}
	public function getCustomerTransactionalEmail()
	{
		$arr=array();
		$arr[0]['value'] = 'flexibleforms_options_admin_email_configuration_customer_email_template';
		$arr[0]['label'] = $this->__('Flexibleforms Customer Email (Default Template from Locale)');
		$emailCollection = Mage::getModel('core/email_template')->getCollection();
		$i=1;
		foreach($emailCollection as $email)
		{
			$arr[$i]['value'] = $email->getTemplateId();
			$arr[$i]['label'] = $this->__($email->getTemplateCode());
			$i++;
		}
		return $arr;

	}

	public function enableProductNameInEmail()
	{
		$returnvalue =false;
		$strOption = Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/product_attribute');
		if(strlen($strOption) > 0)
	 	{
			$arrOption = explode(',',$strOption);
			if(in_array(1,$arrOption))
			{
				$returnvalue = true;
			}
		}
		return $returnvalue;
	}

	public function enableProductSkuInEmail()
	{
		$returnvalue =false;
		$strOption = Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/product_attribute');
		if(strlen($strOption) > 0)
	 	{
			$arrOption = explode(',',$strOption);
			if(in_array(2,$arrOption))
			{
				$returnvalue = true;
			}
		}
		return $returnvalue;
	}

	public function enableProductUrlInEmail()
	{
		$returnvalue =false;
		$strOption = Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/product_attribute');
		if(strlen($strOption) > 0)
	 	{
			$arrOption = explode(',',$strOption);
			if(in_array(3,$arrOption))
			{
				$returnvalue = true;
			}
		}
		return $returnvalue;
	}
}