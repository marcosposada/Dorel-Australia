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
 * Flexibleforms model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Model_Flexibleforms extends Mage_Core_Model_Abstract
{
    const XML_PATH_RECAPTCHA_ENABLED     = 'flexibleforms_options/captcha_configuration/enable_captcha';
    const XML_PATH_RECAPTCHA_PUBLIC_KEY  = 'flexibleforms_options/captcha_configuration/captcha_public_key';
    const XML_PATH_RECAPTCHA_PRIVATE_KEY = 'flexibleforms_options/captcha_configuration/captcha_private_key';

    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleforms/flexibleforms');
    }

    // Form Frontend Submit button text
    public function getFormButtonText()
    {
        $form_button_text = trim($this->getData('form_button_text'));
        if (strlen($form_button_text) == 0)
            $form_button_text = 'Submit';
        return $form_button_text;
    }

    // To get form title and id into widget option 
    public function toOptionArray()
    {
        $collection = Mage::getModel('flexibleforms/flexibleforms')->getCollection();
        $collection->addFieldToFilter( 'form_status', 1 );

        $formlist = array();
        $formlist[] = array('value' => '', 'label' => Mage::helper('flexibleforms')->__('-- Please Select --'));
        if($collection){
            foreach ($collection as $form) {
                $formlist[] = array('value' => $form->getId(), 'label' => $form->getFormTitle());
            }
        }
        return $formlist;
    }

    // Form submit: Server Side Validation
    public function serverSideValidation($formId, $post)
    {
	$arrFieldsId = array();
	$msg = '';
	if(empty($formId))
        {
            return false;
        }
        else
        {
            //Load form fields from database
            $fieldsValidationModel = Mage::getModel('flexibleforms/fields')->getCollection()
                                        ->addFieldToFilter('form_id', array('eq' => $formId))
                                        ->addFieldToFilter('status', array('eq' => 1));

            //$arrFields=array('select','multiselect','checkbox','sendcopytome');
            $postArrayKey = array_keys($post['options']);
            foreach($fieldsValidationModel as $fields)
            {
                if($fields->getRequired()==1)
                {
                    $key=$fields->getFieldId();
                    $arrFieldsId[$key] = $fields->getType();
                    $arrFieldsTitle[$key] = $fields->getTitle();
                    if (in_array($fields->getFieldId(),$postArrayKey) && !Zend_Validate::is($post['options'][$key], 'NotEmpty'))
                    {
                        if($fields->getType()=='state')
                        {
                            if($post['options'][$key]=='' && $post['state_options'][$key]=='')
                            {
                                $msg = $msg.$fields->getTitle().' is required field <br/>';
                            }
                        }
                        else
                        {
                            $msg = $msg.$fields->getTitle().' is required field <br/>'; 
                        }
                    }

                    //email validate
                    if($fields->getType() == 'email' && !Zend_Validate::is($post['options'][$key], 'EmailAddress') )
                    {
                        $msg = $msg.'Please enter a valid email address. For example johndoe@domain.com.<br/>';	
                    }

                    //Validate file upload validation
                    if($fields->getType() == 'file')
                    {
                        foreach($_FILES['file_options']['name'] as $filekey=>$files)
                        {
                            if($_FILES['file_options']['name'][$key]=='')
                            {
                                $msg = $msg.$fields->getTitle().' is required field <br/>';
                            }
                        }
                    }

                    //Validate image upload 
                    if($fields->getType()=='image')
                    {
                        foreach($_FILES['image_options']['name'] as $filekey=>$files)
                        {
                            if($_FILES['image_options']['name'][$key]=='')
                            {
                                $msg = $msg.$fields->getTitle().' is required field <br/>';
                            }
                        }
                    }
                }
            }

            //Validate form fields(array fields)
            $requiredFields = array_diff_key($arrFieldsId,$post['options']);

            $fileUploadFields = array('file','image');
            foreach($requiredFields as $key=>$arrayFields)
            {
                if(!in_array($arrayFields,$fileUploadFields))
                {
                    $msg = $msg.$arrFieldsTitle[$key].' is required field <br/>';
                }
            }
        }
        if(!empty($msg))
        {
            return $msg;
        }
        else
        {
            return false;
        }
    }

    /**
     * validateFile()
     * 
     * This function is used check validation for file and image field
     *
     **/
    public function validateFile($formid,$fieldkey,$filename,$filesize)
    {
        $formValid='';

        $filename = trim($filename);
        if(!empty($filename))
        {
            $fieldModel= Mage::getModel('flexibleforms/fields');
            //Get record of image restriction such as allowed extension and max size
            $filecollection = $fieldModel->getCollection()
                                    ->addFieldToFilter('form_id', $formid)
                                    ->addFieldToFilter('field_id', $fieldkey)
                                    ->getFirstItem();

            /* check file size validation start */
            //Convert $_FILES size from bytes to kb
            $filesize = (int)$fieldModel->formatBytes($filesize);

            //File max size allowed
            $restrictedsize = (int)$filecollection->getAllowedMaxSize();
            if(isset($restrictedsize) && $restrictedsize >0)
            {
                //Check file size validation
                if($filesize > $restrictedsize)
                {
                    $formValid .= $filename .' must be less than '.$restrictedsize.' KB <br/>';
                }
            }
            /* check file size validation end */

            /* check file extension validation start */
            //Check file extension validation
            $allowedExtension = $fieldModel->getAllowedExtensionArray($filecollection->getAllowedExt());
            if($allowedExtension!=false && !in_array($fieldModel->getFileExtension($filename),$allowedExtension) )
            {
                $formValid .= $fieldModel->getFileExtension($filename) .' file not allowed to upload <br/>';
            }
        }
        return $formValid;
    }

    public function getEmailContent($post)
    {
        $formRecord = Mage::getModel('flexibleforms/fields')
                        ->getCollection()
                        ->addFieldToFilter('form_id',array('eq'=> $post['form_id']))
                        ->addFieldToFilter('status',array('eq'=> 1))
                        ->setOrder('position','ASC');

        $postArrayKey = array_keys($post['options']);
        $arrResult = array();
        foreach($formRecord as $Record)
        {
            $fieldId = $Record->getFieldId();
            $title   = $Record->getTitle();
            $field_type = $Record->getType();
            $value = (isset($post['options'][$fieldId])) ? $post['options'][$fieldId] : '';

            if(!empty($value) || $Record->getType()!='file')
            {
                $arrResult[$title] = $value;
                //combo value fields
                $arrComboFields = array('multiselect','checkbox');
                $options = array();
                if(in_array($Record->getType(),$arrComboFields))
                {
                    foreach($post['options'][$fieldId] as $fielsOptions)
                    {
                        $options[] = '['.trim($fielsOptions).']';
                    }
                    $optionStr = implode(',',$options);
                    $arrResult[$title] = $optionStr;
                }

                //single check box value
                if($Record->getType()=='sendcopytome')
                {
                    if(array_key_exists($Record->getFieldId(),$post['options']) )
                    {
                        $arrResult[$title]="Yes";
                    }
                    else
                    {
                        $arrResult[$title]="No";
                    }
                }
                if($Record->getType()=='email')
                {
                    $arrResult[$title]='<a href="mailto:'.$value.'">'.$value.'</a>';
                }
            }
        }
        $emailContent = '';
        foreach($arrResult as $resultKey=>$result)
        {
            $emailContent .= '<p style="font-size:12px; line-height:16px; margin:0 0 16px 0;"><strong>'. $resultKey .' :</strong> '.$result.' </p>';
        }
        return $emailContent;
    }

    //To get option control value selected, null and different value
    public function getOptionControl($option)
    {
        $arrOption = array( 0 => '');
        if($option){
            foreach($option as $key=>$opt)
            {
                $arr=array();
                $arr = preg_split("@{{value=@", $opt);
                if(count($arr)==1)
                {
                    $arrOption[trim($arr[0])]=trim($arr[0]);
                }
                else
                {
                    $replace=array('\'','"','}');
                    $value=str_replace($replace,'',$arr[1]);
                    $value=str_replace('{{selected','{{selected}}',$value);
                    if(strlen(trim($value)) == 0)
                    {
                        $arrOption[0]=trim($arr[0]);
                    }
                    else{
                        $arrOption[trim($value)]=trim($arr[0]);
                    }
                }
            }
        }
        if(empty($arrOption[0]))
        {
            unset($arrOption[0]);
        }
        return $arrOption;
    }

    // get Captcha Html
    public function getCaptchaHtml($formId)
    {
        // To get data and form Id
        $formModel = Mage::getModel('flexibleforms/flexibleforms')->load($formId);
        // For reCaptcha
        if (Mage::getStoreConfigFlag(self::XML_PATH_RECAPTCHA_ENABLED))
        {
            if($formModel->getEnableCaptcha() == 1)
            {
                //create captcha html-code
                $publickey = Mage::getStoreConfig(self::XML_PATH_RECAPTCHA_PUBLIC_KEY);
                $captcha_code = '<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-sitekey="'.$publickey.'"></div>';
            }
        }
        return $captcha_code;
    }

    /**
     * To get Form Url Key into List page
     */
    public function getFormUrl($formUrlKey)
    {
        $url = Mage::getUrl('flexibleforms', array('_secure'=>true)).$formUrlKey.'.html';
        return  $url;
    }

    public function getFieldByType($formId,$type)
    {
        //country field label instead of value
        $countryFieldCollection = Mage::getModel('flexibleforms/fields')->getCollection()
                                    ->addFieldToFilter('status', array('eq' => 1 ))
                                    ->addFieldToFilter('form_id', array('eq' => $formId ))
                                    ->addFieldToFilter('type', array('eq' => $type ));
        return $countryFieldCollection;
    }

    /**
     * getFieldValue($field_id)
     * 
     * This function is used to show field value on form if default value set on form or if form submission unsuccessful
     *
     **/
    function getFieldValue($field_id)
    {

        if(Mage::getSingleton('customer/session')->isLoggedIn())
        {
            //Retrive user detail
            $customer_data=Mage::getSingleton('customer/session')->getCustomer();
            $field_data = Mage::getModel('flexibleforms/fields')->getCollection()
                            ->addFieldToFilter('field_id', array('eq'=>$field_id))
                            ->addFieldToFilter('status', array('eq'=>1))
                            ->getFirstItem();

            $predefine = $field_data->getPreDefineVar();
            if(!empty($predefine))
            {
                if($field_data->getPreDefineVar()=='{{firstname}}')
                {
                    return $field_data->getFirstname();
                }
            }
        }
        else
        {
            if(isset($field_id) && isset($_SESSION['options'][$field_id]))
            {
                $var = $_SESSION['options'][$field_id]; 
                unset($_SESSION['options'][$field_id]);
                return $var;
            }
        }
    }
    
    /**
     * getAdminEmail($formid)
     * 
     * This function return admin email. priority of admin email as in following sequence
     * - admin email from form
     * - admin email from form configration
     * - store email from store module
     * 
     *
     **/
    public function getAdminEmail($formid)
    {
         $admin_data = Mage::getModel('flexibleforms/flexibleforms')->getCollection()
                 ->addFieldToFilter('form_id', array('eq'=>$formid))
                 ->getFirstItem();

        $adminEmail = $admin_data->getAdminEmailAddress();
        return $adminEmail;
    }
    /**
     * getCustomerEmail($formid)
     * 
     * This function return customer email. priority of customer email as in following sequence
     * 
     *
     **/
    public function getCustomerEmail($formid)
    {
        $customer_data = Mage::getModel('flexibleforms/flexibleforms')->getCollection()
                        ->addFieldToFilter('form_id', array('eq'=>$formid))
                        ->getFirstItem();

        $customerEmail = $customer_data->getCustomerReplyEmail();
        return $customerEmail;
    }

    /**
     * getFieldsWithoutFieldset($formId)
     * 
     * This function is used to retrive all fields which not include in any fieldset
     *
     **/
    public function getFieldsWithoutFieldset($formId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                ->addFieldToFilter('fieldset_id', array('eq'=>0))
                ->addFieldToFilter('form_id', array('eq'=>$formId))
				->addFieldToFilter('type', array('neq'=>'hidden'))//To exlude hidden field from field listing
                ->addFieldToFilter('status', array('eq'=>1))
                ->setOrder('position', 'ASC');

        return $fieldsetModel;
    }
    
    /**
     * countFieldsWithoutFieldset($formId)
     *
     * This function is used to count no. of fields without any fieldset
     *
     **/
    public function countFieldsWithoutFieldset($formId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                ->addFieldToFilter('fieldset_id', array('eq'=>0))
                ->addFieldToFilter('form_id', array('eq'=>$formId))
				->addFieldToFilter('type', array('neq'=>'hidden'))//To exlude hidden field from field listing
                ->addFieldToFilter('status', array('eq'=>1))
                ->count();

        return $fieldsetModel;
    }

    /**
     * getFieldset($formId)
     *
     * This function is used to retrive all fields which include in some fieldset
     *
     **/
    public function getFieldset($formId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fieldset')->getCollection()
                ->addFieldToFilter('form_id', array('eq' => $formId))
                ->addFieldToFilter('fieldset_status', array('eq' => 1))
                ->setOrder('fieldset_position', 'ASC');

        return $fieldsetModel;
    }

    /**
     * getFieldsetFields($fieldsetId)
     *
     * This function is used to retrive all fields which include in some fieldset
     *
     **/
    public function getFieldsetFields($fieldsetId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                 ->addFieldToFilter('fieldset_id', array('eq'=>$fieldsetId))
				 ->addFieldToFilter('type', array('neq'=>'hidden'))//To exlude hidden field from field listing
                 ->addFieldToFilter('status', array('eq'=>1))
                 ->setOrder('position', 'ASC');

        return $fieldsetModel;
    }
    
    /**
     * countFieldsetFields($fieldsetId)
     * 
     * This function is used to count fields which include in particular fieldset
     *
     **/
    public function countFieldsetFields($fieldsetId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                 ->addFieldToFilter('fieldset_id', array('eq'=>$fieldsetId))
				 ->addFieldToFilter('type', array('neq'=>'hidden'))//To exlude hidden field from field listing
                 ->addFieldToFilter('status', array('eq'=>1))
                 ->count();

        return $fieldsetModel;
    }

	/**
     * getHiddenFields($formId)
     * 
     * This function is used to retrive all hidden fields
     *
     **/
    public function getHiddenFields($formId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                ->addFieldToFilter('fieldset_id', array('eq'=>0))
                ->addFieldToFilter('form_id', array('eq'=>$formId))
				->addFieldToFilter('type', array('eq'=>'hidden'))
                ->addFieldToFilter('status', array('eq'=>1))
                ->setOrder('position', 'ASC');

        return $fieldsetModel;
    }

    /**
     * countHiddenFields($formId)
     *
     * This function is used to count no. of hidden fields
     *
     **/
    public function countHiddenFields($formId)
    {
        $fieldsetModel = Mage::getModel('flexibleforms/fields')->getCollection()
                ->addFieldToFilter('fieldset_id', array('eq'=>0))
                ->addFieldToFilter('form_id', array('eq'=>$formId))
				->addFieldToFilter('type', array('eq'=>'hidden'))
                ->addFieldToFilter('status', array('eq'=>1))
                ->count();

        return $fieldsetModel;
    }

    /**
     * getFields($field)
     * 
     * This function is used to retrive field block
     *
     **/
    public function getFields($field)
    {
        //declaration of block to use in model
        $block=Mage::getBlockSingleton('flexibleforms/Form');
        if($field->getType() == 'text'){
            echo $block->getTextHtml($field);
        }
        else if($field->getType() == 'textarea'){
            echo $block->getTextareaHtml($field);
        }
        else if($field->getType() == 'email'){
            echo $block->getEmailHtml($field);
        }
        else if($field->getType() == 'number'){
            echo $block->getNumberHtml($field);
        }
        else if($field->getType() == 'url'){
            echo $block->getUrlHtml($field);
        }
        else if($field->getType() == 'select'){
            echo $block->getSelectHtml($field);
        }
        else if($field->getType() == 'multiselect'){
            echo $block->getMultiselectHtml($field);
        }
        else if($field->getType() == 'checkbox'){
            echo $block->getCheckboxHtml($field);
        }
        else if($field->getType() == 'radio'){
            echo $block->getRadioHtml($field);
        }
        else if($field->getType() == 'date'){
            echo $block->getDateHtml($field);
        }
        else if($field->getType() == 'time'){
            echo $block->getTimeHtml($field);
        }
        else if($field->getType() == 'datetime'){
            echo $block->getDatetimeHtml($field);
        }
        else if($field->getType() == 'image'){
            echo $block->getImageHtml($field);
        }
        else if($field->getType() == 'file'){
            echo $block->getFileHtml($field);
        }
        else if($field->getType() == 'sendcopytome'){
            echo $block->getSendcopytomeHtml($field);
        }
        else if($field->getType() == 'country'){
            echo $block->getCountryHtml($field);
        }
        else if($field->getType() == 'state'){
            echo $block->getStateHtml($field);
        }
        else if($field->getType() == 'terms'){
            echo $block->getTermsHtml($field);
        }
        else if($field->getType() == 'star'){
            echo $block->getStarHtml($field);

        }
	else if($field->getType() == 'hidden'){
            echo $block->getHiddenHtml($field);
        }
    }
    public function removeFilesByForm($formId)
    {
        //Files path
        $pathImage = Mage::getBaseDir().DS.'media/flexibleforms/images'.DS;
        $pathFiles = Mage::getBaseDir().DS.'media/flexibleforms/files'.DS;
        //Get results
        $flexibleformsResult = Mage::getModel('flexibleforms/result')->getCollection()
                                ->addFieldToFilter('form_id', array('eq' => $formId ));
        foreach($flexibleformsResult as $result):
            //Field forms collection
            $flexibleformsfields = Mage::getModel('flexibleforms/fields')->getCollection()
                                    ->addFieldToFilter('form_id', array('eq' => $formId ))
                                    ->addFieldToFilter(array('type','type'), array('file','image'));
            
            //Retrive result from result table
            $serializevalue =$result->getValue();
            $unserlizedArray = unserialize($serializevalue);
            $fieldId=0;
            foreach($flexibleformsfields as $fields):
                $fieldId= $fields->getFieldId();
                $fieldType = $fields->getType();
                if($fieldType=='image' || $fieldType=='file'):
                    $filename =($fieldType == 'file')? $pathFiles.$unserlizedArray[$fieldId] :$pathImage.$unserlizedArray[$fieldId] ;
                    if(file_exists($filename)):
                        unlink($filename);
                    endif;
                endif;
            endforeach;
        endforeach;
    }
    public function removeFilesByField($formId,$fieldId)
    {
        //Files path
        $pathImage = Mage::getBaseDir().DS.'media/flexibleforms/images'.DS;
        $pathFiles = Mage::getBaseDir().DS.'media/flexibleforms/files'.DS;
        //Get results
        $flexibleformsResult = Mage::getModel('flexibleforms/result')->getCollection()
                                ->addFieldToFilter('form_id', array('eq' => $formId ));
        foreach($flexibleformsResult as $result):
            //Field forms collection
            $flexibleformsfields = Mage::getModel('flexibleforms/fields')->getCollection()
                                    ->addFieldToFilter('form_id', array('eq' => $formId ))
                                    ->addFieldToFilter(array('type','type'), array('file','image'));
            
            //Retrive result from result table
            $serializevalue =$result->getValue();
            
            $unserlizedArray = unserialize($serializevalue);
            foreach($flexibleformsfields as $fields):
                $fId= $fields->getFieldId();
                if($fieldId === $fId ):
                    $fieldType = $fields->getType();
                    if($fieldType=='image' || $fieldType=='file'):
                        $filename =($fieldType == 'file')? $pathFiles.$unserlizedArray[$fieldId] :$pathImage.$unserlizedArray[$fieldId] ;
                        if(file_exists($filename)):
                            unlink($filename);
                        endif;
                    endif;
                endif;
            endforeach;
        endforeach;
    }
	/*
	** To get admin email template id
	*/
	public function getAdminEmailTemplateId($formId)
	{
            $formCollection = Mage::getModel('flexibleforms/flexibleforms')->getCollection()
                                ->addFieldToFilter('form_id', array('eq' => $formId ))
                                ->addFieldToFilter('form_status', array('eq' => 1 ))
                                ->addFieldToFilter('enable_admin_email_template', array('eq' => 1 ))
                                ->getFirstItem();

            if($formCollection->getEnableAdminEmailTemplate())
            {
                $templateId = $formCollection->getAdminEmailTemplate();
            }
            else
            {
                $templateId = Mage::getStoreConfig('flexibleforms_options/admin_email_configuration/admin_email_template');
            }
            return $templateId;
	}
	
	/*
	** To get email template id
	*/
	public function getCustomerEmailTemplateId($formId)
	{
		$formCollection = Mage::getModel('flexibleforms/flexibleforms')->getCollection()
                                    ->addFieldToFilter('form_id', array('eq' => $formId ))
                                    ->addFieldToFilter('form_status', array('eq' => 1 ))
                                    ->addFieldToFilter('enable_customer_email_template', array('eq' => 1 ))
                                    ->getFirstItem();

		if($formCollection->getEnableCustomerEmailTemplate())
		{
			$templateId = $formCollection->getCustomerEmailTemplate();
		}
		else
		{
			$templateId = Mage::getStoreConfig('flexibleforms_options/customer_email_configuration/customer_email_template');
		}
		return $templateId;
	}
	/**
	* Get Inquiry form id
    */
    public function getProductInquiryFormId()
    {
        return Mage::getStoreConfig('flexibleforms_options/product_inquiry_configuration/flexibleforms_list');
    }
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
}