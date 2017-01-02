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

class Pixlogix_Flexibleforms_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_FLEXIBLEFORM_ENABLED    = 'flexibleforms_options/general/enable';
    const XML_PATH_STORE_RESULT_DATABASE   = 'flexibleforms_options//general/enable_store_result_database';
    const XML_PATH_GENERAL_REDIRECT_URL	   = 'flexibleforms_options/general/redirect_url';
    const XML_PATH_RECAPTCHA_ENABLED       = 'flexibleforms_options/captcha_configuration/enable_captcha';
    const XML_PATH_RECAPTCHA_PUBLIC_KEY    = 'flexibleforms_options/captcha_configuration/captcha_public_key';
    const XML_PATH_RECAPTCHA_PRIVATE_KEY   = 'flexibleforms_options/captcha_configuration/captcha_private_key';

    const XML_PATH_ADMIN_EMAIL_ENABLED     = 'flexibleforms_options/admin_email_configuration/enable_to_admin';
    const XML_PATH_ADMIN_EMAIL_NAME  	   = 'flexibleforms_options/admin_email_configuration/admin_email_name';
    const XML_PATH_ADMIN_EMAIL_ADDRESS	   = 'flexibleforms_options/admin_email_configuration/admin_email_address';
    const XML_PATH_ADMIN_FROM_EMAIL_ADDRESS = 'flexibleforms_options/admin_email_configuration/admin_from_email_address';
    const XML_PATH_ADMIN_EMAIL_SUBJECT	   = 'flexibleforms_options/admin_email_configuration/admin_email_subject';
    const XML_PATH_ADMIN_EMAIL_TEMPLATE_ID = 'flexibleforms_options/admin_email_configuration/admin_email_template';

    const XML_PATH_CUSTOMER_EMAIL_ENABLED = 'flexibleforms_options/customer_email_configuration/enable_to_customer';
    const XML_PATH_CUSTOMER_EMAIL_NAME    = 'flexibleforms_options/customer_email_configuration/customer_email_name';
    const XML_PATH_CUSTOMER_EMAIL_ADDRESS = 'flexibleforms_options/customer_email_configuration/customer_email_address';
    const XML_PATH_CUSTOMER_EMAIL_SUBJECT = 'flexibleforms_options/customer_email_configuration/customer_email_subject';
    const XML_PATH_CUSTOMER_EMAIL_TEMPLATE_ID = 'flexibleforms_options/customer_email_configuration/customer_email_template';

    /**
     * index Action
     *
     * @return Form index page
     */
    public function indexAction()
    {
        // To prevent form if records not found or module disabled
        $collection = Mage::getModel('flexibleforms/flexibleforms')
                            ->getCollection()
                            ->addFieldToFilter('form_status', array('eq' => 1 ));


        $_isFormEnabled = Mage::getStoreConfigFlag(self::XML_PATH_FLEXIBLEFORM_ENABLED);

        if( !$_isFormEnabled )
        {
            $this->_forward('NoRoute');
            return;
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Flexibleforms'));

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home',
            array(
                'label' => Mage::helper('flexibleforms')->__('Home'),
                'title' => Mage::helper('flexibleforms')->__('Home'),
                'link'  => Mage::getBaseUrl()
            )
        );

        $breadcrumbs->addCrumb('flexibleforms',
            array(
                'label' => Mage::helper('flexibleforms')->__('Flexibleforms'),
                'title' => Mage::helper('flexibleforms')->__('Flexibleforms')
            )
        );

        $this->renderLayout();
    }

    /**
     * view Action
     *
     * @return Form view page
     */
    public function viewAction()
    {
        // To get data and form Id
        $id         = $this->getRequest()->getParam('id');
        $url        = $this->getRequest()->getParam('url');
        $collection = Mage::getModel('flexibleforms/flexibleforms')
                        ->getCollection()
                        ->addFieldToFilter('form_status', array('eq' => 1 ));

        if(!empty($url))
        {
            $collection = $collection->addFieldToFilter('form_url_key', array('eq' => $url ))->getFirstItem();
            $id = $this->getRequest()->setParam('id',$collection->getFormId());
        }
        else
        {
            $collection = Mage::getModel('flexibleforms/flexibleforms')->getCollection()->addFieldToFilter('form_id', array('eq' => $id ))->getFirstItem();
        }
		$formId = $collection->getFormId();
		$product_formId = Mage::helper('flexibleforms')->getProductInquiryFormId();
		
		//Redirect to home page if form is selected as product inquiry form
		$enabledProductInquiry =Mage::getModel('flexibleforms/flexibleforms')->enabledProductInquiry();
		if($formId === $product_formId && $enabledProductInquiry):
			Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl());
		endif;
		
        $_isFormEnabled = Mage::getStoreConfigFlag(self::XML_PATH_FLEXIBLEFORM_ENABLED);
        if( !$collection->getData() || !$_isFormEnabled )
        {
            $this->_forward('NoRoute');
            return;
        }

        $pageTitle  = $collection->getFormTitle();
        $pageUrlKey = $collection->getFormUrlKey();
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__($pageTitle));

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home',
            array(
                'label' => Mage::helper('flexibleforms')->__('Home'),
                'title' => Mage::helper('flexibleforms')->__('Home'),
                'link'  => Mage::getBaseUrl()
            )
        );
        $breadcrumbs->addCrumb('flexibleforms',
            array(
                'label' => Mage::helper('flexibleforms')->__('Flexibleforms'),
                'title' => Mage::helper('flexibleforms')->__('Flexibleforms'),
                'link'  => Mage::getUrl('flexibleforms')
            )
        );
        $breadcrumbs->addCrumb($pageUrlKey,
            array(
                'label' => Mage::helper('flexibleforms')->__($pageTitle),
                'title' => Mage::helper('flexibleforms')->__($pageTitle)
            )
        );
        $this->renderLayout();
    }

    /**
     * submit Action to Form Submit
     */
    public function submitAction()
    {
        ob_start();
        $post = $this->getRequest()->getPost();
        
        if ( $post ) {
            try {
                $formData = new Varien_Object();
                $formData->setData($post);
                Mage::getSingleton('core/session')->setData('form', $formData);

                //Call Flexibleform model
                $flexibleformsModel = Mage::getModel('flexibleforms/flexibleforms');
                //Call Fields model
                $fieldModel = Mage::getModel('flexibleforms/fields');
                //Call Result model
                $resultModel = Mage::getModel('flexibleforms/result');

                // To get user info
                $post['sender_ip']    = $this->getRequest()->getServer('REMOTE_ADDR');
                $post['submit_time']  = date("d-m-Y h:i:s A");
                $post['browser_info'] = $this->getRequest()->getServer('HTTP_USER_AGENT');

                // To get form Id and Data
                $formId = $this->getRequest()->getPost('form_id');

                /**
                 * Error msg if no field on form
                 * Return to form page if no field in form
                 **/
                $fieldsCount = $fieldModel->formFieldExists($formId);
                if(!$fieldsCount)
                {
                    Mage::getSingleton('core/session')->addError($this->__('There is no field available. Please, try again later'));
                    return $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                }

                $formModel = $flexibleformsModel->load($formId);

                /**
                * Validate server side validation
                **/
                $formValid = '';
                $formValid = $flexibleformsModel->serverSideValidation($post['form_id'], $post);

                /**
                * Validate server side validation for image field
                **/
                if(isset($_FILES['image_options']))
                {
                    foreach($_FILES['image_options']['name'] as $key => $value)
                    {
                        $formValid .= $formModel->validateFile($formId,$key,$_FILES['image_options']['name'][$key],$_FILES['image_options']['size'][$key]);
                    }
                }

                /**
                * Validate server side validation for file field
                **/
                if(isset($_FILES['file_options']))
                {
                    foreach($_FILES['file_options']['name'] as $key => $value)
                    { 
                        $formValid .= $formModel->validateFile($formId,$key,$_FILES['file_options']['name'][$key],$_FILES['file_options']['size'][$key]);
                    }
                }

                /**
                * Validate captcha if it enable
                **/
                if (Mage::getStoreConfigFlag(self::XML_PATH_RECAPTCHA_ENABLED))
                {
                    if($formModel->getEnableCaptcha() == 1)
                    {
                        //include reCaptcha library
                        require_once(Mage::getBaseDir('lib') . DS .'pixlogixreCaptcha'. DS .'recaptchalib.php');
                        //validate captcha
                        $privatekey = Mage::getStoreConfig(self::XML_PATH_RECAPTCHA_PRIVATE_KEY);
                        $remote_addr = $this->getRequest()->getServer('REMOTE_ADDR');

                        // empty response
                        $response = null;
                        // check secret key
                        $reCaptcha = new ReCaptcha($privatekey);
                        // if submitted check response
                        if ($post['g-recaptcha-response']) {
                            $response = $reCaptcha->verifyResponse( $remote_addr, $post['g-recaptcha-response'] );
                        }
                        if ($response == null )
                        {
                                foreach($post['options'] as $key =>$option){
                                    $_SESSION['options'][$key] = $option;
                                    if(isset($option['state_options']) && !empty($option['state_options']))
                                    {
                                        $_SESSION['state_options']=$option['state_options'];
                                    }
                                }
                                $formValid .= $this->__("The reCAPTCHA wasn't entered correctly. Go back and try it again.");
                                $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                                throw new Exception($formValid, 1);
                        }
                    }
                }

                /**
                * Redirect to form if form not validate
                **/
                if($formValid != '')
                {
                    foreach($post['options'] as $key =>$option){
                        $_SESSION['options'][$key] = $option;
                        if(isset($option['state_options']) && !empty($option['state_options']))
                        {
                            $_SESSION['state_options']=$option['state_options'];
                        }
                    }
                    Mage::getSingleton('core/session')->addError($this->__($formValid));
                    $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                    return;
                }
                // To check form id exist or not
                if($formId)
                {
                    // To insert record into result table
                    $imageArray=array();
                    if(isset($_FILES['image_options']))
                    {
                        foreach($_FILES['image_options']['name'] as $key => $value)
                        {
                            if(isset($_FILES['image_options']['name'][$key]) && !empty($_FILES['image_options']['name'][$key]))
                            {
                                try
                                {
                                    $path = Mage::getBaseDir().DS.'media/flexibleforms/images'.DS;
                                    $imagename = $_FILES['image_options']['name'][$key];
                                    $imagename = str_replace(' ', '_', $imagename);
                                    $uploader = new Varien_File_Uploader(
                                        array(
                                            'name'      => $_FILES['image_options']['name'][$key],
                                            'type'      => $_FILES['image_options']['type'][$key],
                                            'tmp_name'  => $_FILES['image_options']['tmp_name'][$key],
                                            'error'     => $_FILES['image_options']['error'][$key],
                                            'size'      => $_FILES['image_options']['size'][$key]
                                        )
                                    );
                                    $uploader->setAllowedExtensions(array('jpg','jpeg','png','gif'));
                                    $uploader->setAllowCreateFolders(true);
                                    $uploader->setAllowRenameFiles(true);
                                    $uploader->setFilesDispersion(false);
                                    $destFile = $path.$imagename;
                                    $imagename= $resultModel->getNewFileName($destFile);
                                    $imageArray[$key]['name']=$imagename;
                                    $imageArray[$key]['type']=$_FILES['image_options']['type'][$key];
                                    $imageArray[$key]['path']=$path.$imagename;
                                    $uploader->save($path,$imagename);
                                    $post['options'][$key]=$imagename;
                                } catch (Exception $e) {
                                    echo 'Error Message: '.$e->getMessage();
                                }
                            }
                        }
                    }
                    $fileArray=array();
                    if(isset($_FILES['file_options']))
                    {
                        foreach($_FILES['file_options']['name'] as $key => $value)
                        {
                            if(isset($_FILES['file_options']['name'][$key]) && !empty($_FILES['file_options']['name'][$key]))
                            {
                                $path = Mage::getBaseDir().DS.'media/flexibleforms/files/'.DS;
                                $filename = $_FILES['file_options']['name'][$key];
                                $filename = str_replace(' ', '_', $filename);
                                $uploader = new Varien_File_Uploader(
                                    array(
                                        'name'      => $_FILES['file_options']['name'][$key],
                                        'type'      => $_FILES['file_options']['type'][$key],
                                        'tmp_name'  => $_FILES['file_options']['tmp_name'][$key],
                                        'error'     => $_FILES['file_options']['error'][$key],
                                        'size'      => $_FILES['file_options']['size'][$key]
                                    )
                                );
                                $uploader->setAllowCreateFolders(true);
                                $uploader->setAllowRenameFiles(true);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path.$filename;
                                $filename = $resultModel->getNewFileName($destFile);
                                $fileArray[$key]['name']=$filename;
                                $fileArray[$key]['type']=$_FILES['file_options']['type'][$key];
                                $fileArray[$key]['path']=$path.$filename;
                                $uploader->save($path, $filename);
                                $post['options'][$key] = $filename;
                            }
                        }
                    }

                    //Retirve all fields with country as field type
                    $countryFormCollection = $flexibleformsModel->getFieldByType($formId,'country');
                    foreach($countryFormCollection as $countryFieldCollection)
                    {
                        $fieldId=$countryFieldCollection->getFieldId();
                        $post['options'][$fieldId] = $fieldModel->getCountryName($post['options'][$fieldId]);
                    }

                    //Retirve all fields with state as field type
                    $stateFieldCollection = $flexibleformsModel->getFieldByType($formId,'state');
                    foreach($stateFieldCollection as $stateFormCollection)
                    {
                        $fieldId=$stateFormCollection->getFieldId();
                        if(!empty($post['state_options'][$fieldId])){
                            $state = Mage::getModel('directory/region')->load($post['state_options'][$fieldId]);
                            $post['options'][$fieldId] = $state->getName();
                        }
                        else
                        {
                            $post['options'][$fieldId] = $post['options'][$fieldId];
                        }
                    }

                    //Retirve all fields with time field type
                    $timeFieldCollection = $flexibleformsModel->getFieldByType($formId,'time');
                    foreach($timeFieldCollection as $timeFormCollection)
                    {
                        $timefieldId=$timeFormCollection->getFieldId();
                        if(!empty($post['options'][$timefieldId])){
                            $hour = ($post['options'][$timefieldId]['hour']) ? $post['options'][$timefieldId]['hour'] : '00';
                            $minute = ($post['options'][$timefieldId]['minute']) ? $post['options'][$timefieldId]['minute'] : '00';
                            $day_part = ($post['options'][$timefieldId]['day_part']) ? $post['options'][$timefieldId]['day_part'] : '00';
                            $timefield = $hour . ':'. $minute .' '.$day_part;
                            settype($post['options'][$timefieldId], "string");
                            $post['options'][$timefieldId] = (string)$timefield;
                        }
                    }

                    //Send copy to if not check value 2 will be set default
                    $sendcopyfields = $flexibleformsModel->getFieldByType($formId,'sendcopytome');
                    if($sendcopyfields)
                    {
                        foreach($sendcopyfields as $sendcopy)
                        {
                            $sendcopyId = '';
                            $sendcopyId = $sendcopy->getFieldId();
                            if(!array_key_exists($sendcopyId,$post['options']))
                            {
                                $post['options'][$sendcopyId]='2';
                            }
                        }
                    }

                    //Send copy to if not check value 2 will be set default
                    $termsfields = $flexibleformsModel->getFieldByType($formId,'terms');
                    if($termsfields)
                    {
                        foreach($termsfields as $terms)
                        {
                            $termsId = '';
                            $termsId = $terms->getFieldId();
                            if(isset($post['options'][$termsId]))
                            {
                                $post['options'][$termsId]=$post['options'][$termsId][0];
                            }
                            else
                            {
                                if(!array_key_exists($termsId,$post['options']))
                                {
                                    $post['options'][$termsId]='No';
                                }
                            }
                        }
                    }

                    // For data serialize and remove html tags before save into database table
                    $optionsDataArray = array();
                    foreach($post['options'] as $key => $test){
                       $optionsDataArray[$key] = $test;
                    }
                    $serializedValue  = serialize($optionsDataArray);
                    if(Mage::getStoreConfigFlag(self::XML_PATH_FLEXIBLEFORM_ENABLED))
                    {
                        // To save records into database table
                        /*$resultModel->setData($post)
                                    ->setFormId($this->getRequest()->getParam('form_id'))
                                    ->setValue($serializedValue)
                                    ->save();*/

                        // To save records into database table
                        $saveResultModel = $resultModel->setData($post)
                                    ->setFormId($this->getRequest()->getParam('form_id'))
                                    ->setValue($serializedValue);
                                    //if it is product inquiry form than adding product id in result table
                                    if($post['productid']):
                                        $saveResultModel->setProductId($post['productid']);
                                    endif;
                        $saveResultModel->save();
                    }
                    /**************************************************************************************************/
                    /*********************          Send Email Notification Configuration       ***********************/
                    /**************************************************************************************************/
                    // To include selected field value as email subject
                    $sitename   = Mage::getStoreConfig('general/store_information/name') .' - ';

                    // To include selected field value as email subject
                    $adminsubjectFieldId =$fieldModel->getAdminMailSubjectFieldId($formId);
                    $emailSubject = ($adminsubjectFieldId) ? $post['options'][$adminsubjectFieldId]:'';

                    // To get current form title
                    $formTitle	= $formModel->getFormTitle() ?  $formModel->getFormTitle() : '';
                    $emailTemplateSubject = $sitename . $formTitle;

                    // Full Result data for email
                    $resultEmailTemplate = Mage::getModel('flexibleforms/flexibleforms')->getEmailContent($post);

                    /*
                     * Loads the html file named 'flexibleforms_form.html' from
                     * app/locale/en_US/template/email/flexibleforms_form.html
                     */
                    $emailTemplate = Mage::getModel('core/email_template');

                    // Get Store ID
                    $storeId = Mage::app()->getStore()->getId();

                    // Email Template ID
                    $templateId = $flexibleformsModel->getAdminEmailTemplateId($formId);

                    $customertemplateId = $flexibleformsModel->getCustomerEmailTemplateId($formId);

                    // Retirve admin id if not than it take store email id
                    if($flexibleformsModel->getAdminEmail($formId)){
                        $adminEmail = $flexibleformsModel->getAdminEmail($formId);
                    }
                    else
                    {
                        $adminEmail  = Mage::helper('flexibleforms')->getAdminConfigEmail();
                    }
                    $adminName = Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_NAME);
                    
                    // Retirve admin id if not than it take store email id
                    if($flexibleformsModel->getAdminEmail($formId)){
                        $admin_from_email_address = $flexibleformsModel->getAdminEmail($formId);
                    }
                    else
                    {
                        $admin_from_email_address = Mage::helper('flexibleforms')->getAdminReplyToEmail();
                    }
                    
                    // Create an array of variables to assign to template
                    $emailTemplateVariables = array();
                    if($post['productid']):
                            if(Mage::helper('flexibleforms')->enabledProductInquiry()):
                                    $enableproductname= '';
                                    $enableproductsku = '';
                                    $_product = Mage::getModel('catalog/product')->loadByAttribute('entity_id',$post['productid']);
                                    if(Mage::helper('flexibleforms')->enableProductNameInEmail()) : 
                                            $emailTemplateVariables['productname']  = $this->__($_product['name']);
                                            $enableproductname ='Yes';
                                            if(Mage::helper('flexibleforms')->enableProductUrlInEmail()) : 
                                                    $emailTemplateVariables['productname']  = '<a href="'.Mage::getBaseUrl().$_product['url_path'].'">'.$this->__($_product['name']).'</a>';
                                            endif;
                                    endif;

                                    if(Mage::helper('flexibleforms')->enableProductSkuInEmail()):
                                            $emailTemplateVariables['productsku']  = $this->__($_product['sku']);
                                            $enableproductsku = 'Yes';
                                    endif;
                                    $enableproductinquiry = 'yes';
                            endif;
                    endif;
                    $emailTemplateVariables['enableproductname'] = $enableproductname;
                    $emailTemplateVariables['enableproductsku'] = $enableproductsku;
                    $emailTemplateVariables['formtitle']  = $formModel->getFormTitle();
                    $emailTemplateVariables['adminemail'] = $adminEmail;
                    $emailTemplateVariables['useremail']  = $admin_from_email_address;
                    $emailTemplateVariables['result']     = $resultEmailTemplate;
                    $emailTemplateVariables['sender_ip']  = $post['sender_ip'];

                    // To set store timezone in email submit time
                    $timezone = Mage::getStoreConfig('general/locale/timezone');
                    date_default_timezone_set($timezone);
                    $current_time = now();
                    $submit_time = date('d-m-Y h:i:s A',strtotime($current_time));
                    $emailTemplateVariables['submit_time']= $submit_time;
                    $emailTemplateVariables['browser_info']= $post['browser_info'];

                    // To get Sender Email field value to email
                    $fieldsModelForUserEmail = Mage::getModel('flexibleforms/fields')
                                                ->getCollection()
                                                ->addFieldToFilter('form_id', $formId)
                                                ->addFieldToFilter('type', 'email')
                                                ->getFirstItem();

                    $emailFieldId = $fieldsModelForUserEmail->getFieldId();
                    $userEmail = $post['options'][$emailFieldId] ? $post['options'][$emailFieldId] : '';

                    if($userEmail)
                    {
                        $replyTo =$userEmail;
                    }
                    else
                    {
                        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                            $customer = Mage::getSingleton('customer/session')->getCustomer();
                            $replyTo=$customer->getEmail();
                        }
                        $replyTo='';
                    }
                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /* Send Email Notification to Admin
                    ************************************************************************************************/
                    $name='';
                    if($adminEmailEnabled = Mage::getStoreConfig(self:: XML_PATH_ADMIN_EMAIL_ENABLED) && $formModel['enable_admin_email'] )
                    {
                            // To get admin email subject
                            if($emailSubject=='')
                            {
                                    
                                    $adminEmailSubject = (Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_SUBJECT)) ? ' | '.Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_SUBJECT) : '';
                                    $adminEmailTemplateSubject = $emailTemplateSubject . $adminEmailSubject;
                            }
                            else
                            {
                                    $emailSubject = ($emailSubject) ? ' | '.$emailSubject : '';
                                    $adminEmailTemplateSubject = $emailTemplateSubject . $emailSubject;
                            }
                            // To get admin email address from System Configuration
                            $arrayAdminEmail = explode(',',$adminEmail);

                            /**
                             * Send transactional email to recipient
                             *
                             * @param   int $templateId
                             * @param   string|array $sender sender information, can be declared as part of config path
                             * @param   string $email recipient email
                             * @param   string $name recipient name
                             * @param   array $vars varianles which can be used in template
                             * @param   int|null $storeId
                             * @return  Mage_Core_Model_Email_Template
                             */
                            // Sender/From Name and Email
                            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                                    $sender=array(
                                            'name' => $customer->getName(),
                                            'email' => $admin_from_email_address,
                                    );
                            }
                            else if(isset($adminName) && !empty($adminName))
                            {
                                    $sender = Array(
                                            'name'	=> $adminName,
                                            'email'	=> $admin_from_email_address,
                                    );
                            }
                            else{
                                    $sender=array(
                                            'name' => $this->__('Guest'),
                                            'email'=> $admin_from_email_address
                                    );
                            }

                            $emailTemplate->setTemplateSubject($adminEmailTemplateSubject);
                            //Add file attachment to admin email
                            $emailTemplate = $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
                            //Image field attachement
                            if(count($imageArray)>0)
                            {
                                foreach($imageArray as $imageKey=>$imageValue){
                                    $emailTemplate->getMail()->createAttachment(file_get_contents($imageValue['path']), $imageValue['type'])->filename = basename($imageValue['path']);
                                }
                            }

                            //File field attachement
                            if(count($fileArray)>0)
                            {
                                foreach($fileArray as $fileKey=>$fileValue){
                                    $emailTemplate->getMail()->createAttachment(file_get_contents($fileValue['path']), $fileValue['type'])->filename = basename($fileValue['path']);
                                }
                            }
                            $emailTemplate->setReplyTo($replyTo)->sendTransactional($templateId, $sender, $arrayAdminEmail, $name, $emailTemplateVariables, $storeId);          
                            
                    }

                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /* Send Email Notification to Customer
                    ************************************************************************************************/

                    if($emailSubject=='')
                    {
                            $customerSubject = Mage::helper('flexibleforms')->getCustomerSubject();
                            $customerEmailSubject = ($customerSubject) ? ' | '.Mage::helper('flexibleforms')->getCustomerSubject() : '';
                            $customerEmailTemplateSubject = $emailTemplateSubject . $customerEmailSubject;
                    }
                    else
                    {
                            $customerEmailTemplateSubject = $emailTemplateSubject . $emailSubject;
                    }

                    if($flexibleformsModel->getCustomerEmail($formId)){
                       $customerEmailSender = $flexibleformsModel->getCustomerEmail($formId);
                    }
                    else
                    {
                        $customerEmailSender = Mage::getStoreConfig(self:: XML_PATH_CUSTOMER_EMAIL_ADDRESS);
                    }
                    $customerSenderName = Mage::getStoreConfig(self:: XML_PATH_CUSTOMER_EMAIL_NAME);

                    $fieldsModelForSendCopy = Mage::getModel('flexibleforms/fields')
                                                ->getCollection()
                                                ->addFieldToFilter('form_id', $formId)
                                                ->addFieldToFilter('type', 'sendcopytome')
                                                ->addFieldToFilter('status', '1')
                                                ->getFirstItem();

                    // If "Send copy to me" is enabled from form, Then customer will receive email
                    $fieldidSendcopy = $fieldsModelForSendCopy->getFieldId();
                    if(isset($fieldidSendcopy) && $post['options'][$fieldidSendcopy] == '1' )
                    {
                            /**
                             * Send transactional email to recipient
                             *
                             * @param   int $templateId
                             * @param   string|array $sender sender information, can be declared as part of config path
                             * @param   string $email recipient email
                             * @param   string $name recipient name
                             * @param   array $vars varianles which can be used in template
                             * @param   int|null $storeId
                             * @return  Mage_Core_Model_Email_Template
                             */
                            // Sender/From Name and Email
                            if(empty($customerSenderName))
                            {
                                $customerSenderName = Mage::app()->getStore()->getName();
                            }
                            $sender = Array(
                                    'name'	=> $customerSenderName,
                                    'email'	=> $customerEmailSender,
                            );
                            $emailTemplate->setTemplateSubject($customerEmailTemplateSubject);

                            //Add attachement to customer email
                            $emailTemplate = $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
                            //Image field attachement
                            if(count($imageArray)>0)
                            {
                                    foreach($imageArray as $imageKey=>$imageValue){
                                            $emailTemplate->getMail()->createAttachment(file_get_contents($imageValue['path']), $imageValue['type'])->filename = basename($imageValue['path']);
                                    }
                            }

                            //File field attachement
                            if(count($fileArray)>0)
                            {
                                    foreach($fileArray as $fileKey=>$fileValue){
                                            $emailTemplate->getMail()->createAttachment(file_get_contents($fileValue['path']), $fileValue['type'])->filename = basename($fileValue['path']);
                                    }
                            }
                            $emailTemplate->sendTransactional($customertemplateId, $sender, $userEmail, $name, $emailTemplateVariables, $storeId);
                    }

                    // If "Send copy to me" not in a form, Then customer will receive email
                    
                    $userEmailEnabled = Mage::getStoreConfigFlag(self:: XML_PATH_CUSTOMER_EMAIL_ENABLED);

                    if(!isset($fieldidSendcopy) && $userEmailEnabled && $formModel->getEnableCustomerEmail() && $userEmail!='' )
                    {
                            /**
                             * Send transactional email to recipient
                             *
                             * @param   int $customertemplateId
                             * @param   string|array $sender sender information, can be declared as part of config path
                             * @param   string $email recipient email
                             * @param   string $name recipient name
                             * @param   array $vars varianles which can be used in template
                             * @param   int|null $storeId
                             * @return  Mage_Core_Model_Email_Template
                             */
                            // Sender/From Name and Email
                            if(empty($customerSenderName))
                            {
                                $customerSenderName = Mage::app()->getStore()->getName();
                            }
                            $sender = Array(
                                    'name'	=> $customerSenderName,
                                    'email'	=> $customerEmailSender,
                            );
                            $emailTemplate->setTemplateSubject($customerEmailTemplateSubject);
                            if(count($imageArray)>0)
                            {
                                    foreach($imageArray as $imageKey=>$imageValue){
                                            $emailTemplate->getMail()->createAttachment(file_get_contents($imageValue['path']), $imageValue['type'])->filename = basename($imageValue['path']);
                                    }
                            }
                            //File field attachement
                            if(count($fileArray)>0)
                            {
                                    foreach($fileArray as $fileKey=>$fileValue){
                                            $emailTemplate->getMail()->createAttachment(file_get_contents($fileValue['path']), $fileValue['type'])->filename = basename($fileValue['path']);
                                    }
                            }
                            $emailTemplate->sendTransactional($customertemplateId, $sender, $userEmail, $name, $emailTemplateVariables, $storeId);
                    }
                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /**************************************************************************************************/
                    /* Send Email Notification to Customer
                    ************************************************************************************************/

                    if($formModel->getFormSuccessDescription())
                    {
                        $form_success_description = Mage::helper('cms')->getBlockTemplateProcessor()->filter($formModel->getFormSuccessDescription());
                        Mage::getSingleton('core/session')->addSuccess(Mage::helper('flexibleforms')->__($form_success_description));
                    }else{
                        Mage::getSingleton('core/session')->addSuccess(Mage::helper('flexibleforms')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                    }

                    // To redirect after successful form submit
                    if ( $formModel->getFormRedirectUrl() ) {
                        $this->_redirectUrl($formModel->getFormRedirectUrl());
                    } else if ( Mage::getStoreConfig(self::XML_PATH_GENERAL_REDIRECT_URL) ) {
                        $this->_redirectUrl(Mage::getStoreConfig(self::XML_PATH_GENERAL_REDIRECT_URL));
                    } else {
                        $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                    }
                    return;
                } else {
                    // To Error message after form submit
                    if( $formModel->getFormFailDescription() )
                    {
                        $form_fail_description = Mage::helper('cms')->getBlockTemplateProcessor()->filter($formModel->getFormFailDescription());
                        Mage::getSingleton('core/session')->addError(Mage::helper('flexibleforms')->__($form_fail_description));
                    }else{
                        foreach($post['options'] as $key =>$option){
                            $_SESSION['options'][$key] = $option;
                            if(isset($option['state_options']) && !empty($option['state_options']))
                            {
                                $_SESSION['state_options']=$option['state_options'];
                            }
                        }
                        Mage::getSingleton('core/session')->addError(Mage::helper('flexibleforms')->__('Unable to submit your request. Please, try again later'));
                    }
                    // To form redirect
                    $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                }
                Mage::getSingleton('core/session')->unsetData('form');
            }
            catch (Exception $e)
            {
                foreach($post['options'] as $key =>$option){
                    $_SESSION['options'][$key] = $option;
                    if(isset($option['state_options']) && !empty($option['state_options']))
                    {
                        $_SESSION['state_options']=$option['state_options'];
                    }
                }
                if (strlen($e->getMessage()) > 0) {
                    Mage::getSingleton('core/session')->addError($this->__($e->getMessage()));
                }
                $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                return;
            }
        } else {
            foreach($post['options'] as $key =>$option){
                $_SESSION['options'][$key] = $option;
                if(isset($option['state_options']) && !empty($option['state_options']))
                {
                    $_SESSION['state_options']=$option['state_options'];
                }
            }
            // To Error message after form submit
            Mage::getSingleton('core/session')->addError(Mage::helper('flexibleforms')->__('Unable to submit your request. Please, try again later'));
            $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
        }
    }
}