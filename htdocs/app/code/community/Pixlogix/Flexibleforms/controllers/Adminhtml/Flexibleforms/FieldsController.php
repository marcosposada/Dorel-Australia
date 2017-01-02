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

class Pixlogix_Flexibleforms_Adminhtml_Flexibleforms_FieldsController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
            $this->loadLayout()
                 ->_setActiveMenu('flexibleforms/flexibleforms');
            return $this;
	}

	public function indexAction()
        {
            $this->_initAction();
            $this->renderLayout();
	}

	public function editAction()
	{
            $this->_title($this->__('Flexible Forms'))->_title($this->__('Edit Field'));

            $fieldsId = $this->getRequest()->getParam('id');
            $flexibleformsId = $this->getRequest()->getParam('form_id');

            $field = Mage::getModel('flexibleforms/fields')->load($fieldsId);
            if($field->getFormId()){
                $flexibleformsId = $field->getFormId();
            }

            //To load field option value
            if($fieldsId)
            {
                $fields_record = Mage::getModel('flexibleforms/fields')->loadByFieldId($fieldsId);
                $field_type = $fields_record->getType();

                //To retrive field name(id)
                $field_type_name = Mage::getModel('flexibleforms/fields')->getFieldTypeName($field_type);
                if($field_type_name)
                {
                    //If field name is allowed_ext than it return allowed extension record else it return option value
                    $optionField    = ($field_type_name=='allowed_ext')? $fields_record->getAllowedExt() : $fields_record->getOptionsValue();
                    if($optionField){
                        $arr_options    = unserialize($optionField);
                        $optionCount    = count($arr_options);
                        $optionValues   = implode('',$arr_options);
                        $field->setData($field_type_name, $optionValues);
                    }
                }
            }

            $flexibleformsModel = Mage::getModel('flexibleforms/flexibleforms')->load($flexibleformsId);
            if($field->getId() || $fieldsId == 0)
            {
                Mage::register('flexibleforms_data',$flexibleformsModel);
                Mage::register('field', $field);

                $this->loadLayout();
                $this->_setActiveMenu('flexibleforms/flexibleforms');
                $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Flexible Forms'),Mage::helper('adminhtml')->__('Flexible Forms'));
                $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
                $this->_addContent($this->getLayout()->createBlock('flexibleforms/adminhtml_fields_edit'))
                     ->_addLeft($this->getLayout()->createBlock('flexibleforms/adminhtml_fields_edit_tabs'));
                $this->renderLayout();
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Field does not exist'));
                $this->_redirect('adminhtml/flexibleforms_flexibleforms/edit',array('id' => $flexibleformsId));
            }
	}

	public function newAction()
	{
            $this->_forward('edit');
	}

	public function saveAction()
	{
            if( $this->getRequest()->getPost())
            {
                try{
                    $postData = $this->getRequest()->getPost();
                    $id = $this->getRequest()->getParam("id");
                    $fieldsModel = Mage::getModel("flexibleforms/fields")->load($id);
                    if($fieldsModel->getCreatedTime() == NULL)
                    {
                        $created_time = now();
                    } else {
                        $created_time = $fieldsModel->getCreatedTime();
                    }

                    //This condition return true if field type is select,multiselect,checkbox,radio and terms else it return false
                    if(Mage::getModel('flexibleforms/fields')->checkTextareaField($postData['type']))
                    {
                        // To get optionvalue from textarea to store in field table
                        $optionKey=0;
                        foreach($postData['value'] as $key=>$option):
                            if(isset($option) && !empty($option)):
                                $optionKey=$key;
                            endif;
                        endforeach;
                        // To get field value
                        if($optionKey)
                        {
                            $optionValue = $postData['value'][$optionKey];
                        }
                        else
                        {
                            $optionValue='';
                        }

                        // To break textarea value in array form to store in database
                        if(!empty($optionValue)){
                            $optionArray = preg_split('/$\R?^/m', $optionValue);
                        }else{
                            $optionArray = array();
                        }

                        $option = count($optionArray);
                        $postData['options'] = $option;

                        // To check "$optionArray" has value or not
                        if($option > 0){
                            $optionsValue = serialize($optionArray);
                        }else{
                            $optionArray = '';
                            $optionsValue='';
                        }
                    }
                    else
                    {
                        $postData['options'] = 0;
                        $optionsValue = '';
                    }

                    $fileTypeFields=array('image','file');
                    $fileExtArray ='';
                    if(in_array($postData['type'],$fileTypeFields))
                    {
                        if(!empty($postData['allowed_ext'])){
                            $fileExtArray = preg_split('/$\R?^/m', $postData['allowed_ext']);
                        }else{
                            $fileExtArray = array();
                        }

                        $extensions = count($fileExtArray);
                        // To check "$optionArray" has value or not
                        if($extensions > 0){
                            $fileExtArray = serialize($fileExtArray);
                        }else{
                            $fileExtArray = '';
                        }
                    }
                    else {
                        $postData['allowed_max_size'] = 0;
                        $postData['allowed_ext'] = '';
                    }
                    if($postData['type'] == 'star'){
                        $postData['form_star_max_value'] = ($postData['form_star_max_value']) ? $postData['form_star_max_value'] : '5';
                    }
                    $fieldsModel->setData($postData)
                                ->setId($this->getRequest()->getParam("id"))
                                ->setOptionsValue($optionsValue)
                                ->setAllowedExt($fileExtArray)
                                ->setCreatedTime($created_time)
                                ->setUpdateTime(now())
                                ->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The field has been saved successfully.'));
                    Mage::getSingleton('adminhtml/session')->setFlexibleformsData(false);

                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $fieldsModel->getId(), 'form_id' => $fieldsModel->getFormId(),'active_tab' => 'fieldslist_section'));
                        return;
                    }
                    $this->_redirect('*/flexibleforms_flexibleforms/edit', array('id' => $fieldsModel->getFormId(),'active_tab' => 'fieldslist_section'));
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFlexibleformsData($this->getRequest()->getPost());
                    $this->_redirect('*/edit', array('id' => $this->getRequest()->getParam('id'),'active_tab' => 'fieldslist_section'));
                    return;
                }
            }
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Unexpected error'));
            $this->_redirect('flexibleforms_flexibleforms/edit');
	}

        // Delete Action for field edit page
        public function deleteAction()
        {
            if( $this->getRequest()->getParam('id') > 0){
                try{
                    $field = Mage::getModel('flexibleforms/fields')->load($this->getRequest()->getParam('id'));
                    $form_id = $field->getFormId();
                    $fieldId = $field->getFieldId();
                    $removeFiles = Mage::getModel('flexibleforms/flexibleforms')->removeFilesByField($form_id,$fieldId);
                    $field->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The field has been deleted successfully.'));
                } catch (Exception $e){
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirectReferer();
                }
            }
            $this->_redirect('*/flexibleforms_flexibleforms/edit',array('id' => $form_id, 'active_tab' => 'fieldslist_section'));
        }

    // Mass Delete Action for fields Grid
    public function massDeleteAction()
    {
        $flexiblefieldsIds = $this->getRequest()->getParam('id');
        if(!is_array($flexiblefieldsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexiblefieldsIds as $flexiblefieldsId) {
                    $flexiblefields = Mage::getModel('flexibleforms/fields')->load($flexiblefieldsId);
                    $formId = $flexiblefields->getFormId();
                    $removeFiles = Mage::getModel('flexibleforms/flexibleforms')->removeFilesByField($formId,$flexiblefieldsId);
                    $flexiblefields->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($flexiblefieldsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
	$this->_redirectReferer();
    }

    // Mass Status Update Action fields for Grid
    public function massStatusAction()
    {
        $flexibleformsIds = $this->getRequest()->getParam('id');
        if(!is_array($flexibleformsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexibleformsIds as $flexibleformsId){
                    $flexibleforms = Mage::getSingleton('flexibleforms/fields')
                        ->load($flexibleformsId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($flexibleformsIds))
                );
            }
            catch (Exception $e)
            {
                $this->_getSession()->addError($e->getMessage());
            }
        }
	$this->_redirectReferer();
    }

    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }
} ?>