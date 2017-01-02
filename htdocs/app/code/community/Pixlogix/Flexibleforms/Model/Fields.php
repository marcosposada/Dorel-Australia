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
 * Flexibleforms Fields model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Model_Fields extends Mage_Core_Model_Abstract
{
    protected $formId = 0;

    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleforms/fields');
    }

    public function getFieldTypesOptions()
    {
        $types = new Varien_Object(array(
                "text"		=> Mage::helper('flexibleforms')->__('Text'),
                "textarea"	=> Mage::helper('flexibleforms')->__('Textarea'),
                "email"		=> Mage::helper('flexibleforms')->__('Email'),
                "number"	=> Mage::helper('flexibleforms')->__('Number'),
                "url"		=> Mage::helper('flexibleforms')->__('Url'),
                "select"	=> Mage::helper('flexibleforms')->__('Select'),
                "multiselect"   => Mage::helper('flexibleforms')->__('Multi Select'),
                "checkbox"	=> Mage::helper('flexibleforms')->__('Checkbox'),
                "radio"		=> Mage::helper('flexibleforms')->__('Radio'),
                "date"		=> Mage::helper('flexibleforms')->__('Date'),
                "time"		=> Mage::helper('flexibleforms')->__('Time'),
                "datetime"	=> Mage::helper('flexibleforms')->__('Date Time'),
                "image"		=> Mage::helper('flexibleforms')->__('Image'),
                "file"		=> Mage::helper('flexibleforms')->__('File'),
                "sendcopytome"  => Mage::helper('flexibleforms')->__('Send copy to me'),
                "country" 	=> Mage::helper('flexibleforms')->__('Country'),
                "state" 	=> Mage::helper('flexibleforms')->__('State'),
                "terms" 	=> Mage::helper('flexibleforms')->__('Terms and conditions'),
                "star"      => Mage::helper('flexibleforms')->__('Star Rating'),
				"hidden"	=> Mage::helper('flexibleforms')->__('Hidden'),
        ));
        return $types->getData();
    }

    public function getFormFieldsCollection($formId)
    {
        $fieldsModel = Mage::getModel('flexibleforms/fields');
    	$fieldsCollection = $fieldsModel->getCollection();
        $fieldsCollection->addFieldToFilter('form_id', array('eq'=> $formId));
        $fieldsCollection->addFieldToFilter('status', array('eq'=> 1));
        $fieldsCollection->setOrder('position','asc');
        return $fieldsCollection;
    }

    public function loadByFieldId($fieldid)
    {
        $collection = $this->getCollection()->addFieldToFilter('field_id', $fieldid);
        return $collection->getFirstItem();
    }

    public function getFieldTypeName($type)
    {
        if($type == 'select')
        {
            return 'value_options';
        }
        else if($type == 'multiselect')
        {
            return 'value_options_multi';
        }
        else if($type == 'checkbox')
        {
            return 'value_options_checkbox';
        }
        else if($type == 'radio')
        {
            return 'value_options_radio';
        }
        else if($type == 'terms')
        {
            return 'value_options_terms';
        }
        else if($type == 'image')
        {
            return 'allowed_ext';
        }
        else if($type == 'file')
        {
            return 'allowed_ext';
        }
        else
        {
            return false;	
        }
    }

    public function checkTextareaField($fieldType)
    {
        $array_field = array('select','multiselect','checkbox','radio','terms');
        if( in_array($fieldType,$array_field) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * getFieldTypeName()
     * 
     * This function is used to pass pre define value of fields to data helper class getFieldValue()
     *
     **/
    public function getPreDefineFieldValue($fieldvalue)
    {
        if( !empty($fieldvalue) )
        {

            //Retrive user detail
            $customer_data = Mage::getSingleton('customer/session')->getCustomer();

            //To retrive billing information of user if any field spacify
            $arrBilling = array('{{telephone}}','{{company}}','{{fax}}','{{street}}','{{street-2}}','{{city}}','{{postcode}}','{{country}}','{{state}}');
            if(in_array($fieldvalue,$arrBilling))
            {
                $customer_billing_address=$customer_data->getPrimaryBillingAddress();
            }

            //To retrive billing information of user if any field spacify
            $arrShipping = array('{{shipping-telephone}}','{{shipping-company}}','{{shipping-fax}}','{{shipping-street}}','{{shipping-street-2}}','{{shipping-city}}','{{shipping-postcode}}','{{shipping-country}}','{{shipping-state}}');
            if(in_array($fieldvalue,$arrShipping))
            {
                $customer_shipping_address=$customer_data->getPrimaryShippingAddress();
            }

            if($fieldvalue=='{{firstname}}')
            {
                return $customer_data->getFirstname();
            }
            else if($fieldvalue=='{{middlename}}')
            {
                return $customer_data->getMiddlename();
            }
            else if($fieldvalue=='{{lastname}}')
            {
                return $customer_data->getLastname();
            }
            else if($fieldvalue=='{{email}}')
            {
                return $customer_data->getEmail();
            }
            if($customer_billing_address):
                if($fieldvalue=='{{company}}')
                {
                    return $customer_billing_address->getCompany();
                }
                else if($fieldvalue=='{{telephone}}')
                {
                    //print_r($customer_data);
                    return $customer_billing_address->getTelephone();
                }
                else if($fieldvalue=='{{fax}}')
                {
                    return $customer_billing_address->getFax();
                }
                else if($fieldvalue=='{{street}}')
                {
                    $address =$customer_billing_address->getStreet();
                    return $address[0];
                    //print_r($customer_billing_address->getStreet());
                }
                else if($fieldvalue=='{{street-2}}')
                {
                    return $customer_billing_address->getStreet2();
                }
                else if($fieldvalue=='{{city}}')
                {
                    return $customer_billing_address->getCity();
                }
                else if($fieldvalue=='{{postcode}}')
                {
                    return $customer_billing_address->getPostcode();
                }
                else if($fieldvalue=='{{country}}')
                {
                    $countryModel = Mage::getModel('directory/country')->loadByCode($customer_billing_address->getCountry());
                    return $countryModel->getName();
                }
                else if($fieldvalue=='{{state}}')
                {
                    if($customer_billing_address->getRegionId()!=0)
                    {
                        $region = Mage::getModel('directory/region')->load($customer_billing_address->getRegionId());
                        return $region->getName();
                    }
                    else
                    {
                        return $customer_billing_address->getRegion();
                    }
                }
            endif;
            if($customer_shipping_address):
                //Shipping address information
                if($fieldvalue=='{{shipping-company}}')
                {
                    if($customer_shipping_address->getCompany())
                    {
                        return $customer_shipping_address->getCompany();
                        exit;
                    }
                    return '';

                }
                else if($fieldvalue=='{{shipping-telephone}}')
                {
                    //print_r($customer_data);

                    return $customer_shipping_address->getTelephone();
                }
                else if($fieldvalue=='{{shipping-fax}}')
                {
                    return $customer_shipping_address->getFax();
                }
                else if($fieldvalue=='{{shipping-street}}')
                {
                    $address =$customer_shipping_address->getStreet();
                    return $address[0];
                }
                else if($fieldvalue=='{{shipping-street-2}}')
                {
                    return $customer_shipping_address->getStreet2();
                }
                else if($fieldvalue=='{{shipping-city}}')
                {
                    return $customer_shipping_address->getCity();
                }
                else if($fieldvalue=='{{shipping-postcode}}')
                {
                    return $customer_shipping_address->getPostcode();
                }
                else if($fieldvalue=='{{shipping-country}}')
                {
                    $countryModel = Mage::getModel('directory/country')->loadByCode($customer_shipping_address->getCountry());
                    return $countryModel->getName();
                }
                else if($fieldvalue=='{{shipping-state}}')
                {
                   if($customer_shipping_address->getRegionId()!=0)
                    {
                        $region = Mage::getModel('directory/region')->load($customer_shipping_address->getRegionId());
                        return $region->getName();
                    }
                    else
                    {
                        return $customer_shipping_address->getRegion();
                    }
                }
            endif;
        }
        else
        {
            return $fieldvalue;
        }
    }

    /**
     * formatBytes($bytes, $precision = 0)
     * 
     * This function is used convert bytes into kilobite
     *
     **/
    public function formatBytes($bytes, $precision = 0) { 
        $units = array('B', 'KB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision); 
    }

    /**
     * getFileExtension($filename)
     * 
     * This function is get file extension
     *
     **/
    public function getFileExtension($filename)
    {
        $fileArr=explode('.',$filename);
        $count=count($fileArr);
        return $fileArr[$count-1];
    }
    
    /**
     * getAllowedExtensionArray($fieldvalue)
     * 
     * This function is used to convert serialize data of allowed extension into unserialized
     *
     **/
    public function getAllowedExtensionArray($fieldvalue)
    {
        if(!empty($fieldvalue))
        {
            $arrayField = unserialize($fieldvalue);
            foreach($arrayField as $a)
            {
                $fieldarr[]=trim($a);
            }

            return $fieldarr;
        }
        else
        {
            return false;
        }
    }
    public function formFieldExists($formid)
    {
        $fieldsCount = Mage::getModel('flexibleforms/fields')->getCollection()
                        ->addFieldToFilter('form_id', $formid)
                        ->addFieldToFilter('status','1')
                        ->count();

        return $value = ($fieldsCount > 0 ) ? 'true' : 'false';
    }
    
    public function getCountryName($fieldName)
    {
        $country = Mage::getModel('directory/country')->loadByCode($fieldName);
        return $country->getName();
    }
    
    /**
     * getUserEmailFieldId($formid)
     * 
     * This function is used to retrive user email field id if exists on form
     *
     **/
    public function getUserEmailFieldId($formid)
    {
        
        $fieldModel = Mage::getModel('flexibleforms/fields')->getCollection()
                        ->addFieldToFilter('form_id', $formid)
                        ->addFieldToFilter('type', 'email')
                        ->getFirstItem();

        return $userEmail= ($fieldModel->getFieldId()) ? $fieldModel->getFieldId() : false;
    }

    /**
     * getAdminMailSubjectFieldId($formid)
     * 
     * This function is used to retrive user email field id if exists on form
     *
     **/
    public function getAdminMailSubjectFieldId($formid)
    {
        $fieldModel = Mage::getModel('flexibleforms/fields')->getCollection()
                        ->addFieldToFilter('form_id', $formid)
                        ->addFieldToFilter('type', 'text')
                        ->addFieldToFilter('field_title_to_email', 1)
                        ->getFirstItem();
        return $subjectFieldId= ($fieldModel->getFieldId()) ? $fieldModel->getFieldId() : false;
    }
    /**
     * getFieldType($fieldid)
     * 
     * This function is used to retrive field type
     *
     **/
     public function getFieldByType($fieldid)
    {
        //country field label instead of value
        $fieldCollection = Mage::getModel('flexibleforms/fields')->getCollection()
                                    ->addFieldToFilter('field_id', array('eq' => $fieldid ))
                                    ->getFirstItem();
        return $fieldCollection->getType();
    }
}