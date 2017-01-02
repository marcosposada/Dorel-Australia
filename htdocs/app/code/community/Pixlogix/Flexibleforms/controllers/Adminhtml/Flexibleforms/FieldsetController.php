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

class Pixlogix_Flexibleforms_Adminhtml_Flexibleforms_FieldsetController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('flexibleforms/flexibleforms');
        return $this;
    }

    public function indexAction(){
        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $fieldsetId     = $this->getRequest()->getParam('id');
        $flexibleformsId= $this->getRequest()->getParam('form_id');
        $fieldsetModel  = Mage::getModel('flexibleforms/fieldset')->load($fieldsetId);
        $flexibleformsModel = Mage::getModel('flexibleforms/flexibleforms')->load($flexibleformsId);
        $this->_title( $fieldsetModel->getFieldsetId() ? $this->__('Edit \'%s\' Form', $fieldsetModel->getFieldsetTitle()) : $this->__('Add Form') );

        if ($fieldsetModel->getFieldsetId() || $fieldsetId == 0)
        {
            Mage::register('flexibleforms_data', $flexibleformsModel);
            Mage::register('fieldset', $fieldsetModel);

            $this->loadLayout();
            $this->_setActiveMenu('flexibleforms/flexibleforms');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Forms'), Mage::helper('adminhtml')->__('Manage Forms'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Form Item'), Mage::helper('adminhtml')->__('Form Item'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('flexibleforms/adminhtml_fieldset_edit'))
                        ->_addLeft($this->getLayout()->createBlock('flexibleforms/adminhtml_fieldset_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Fieldset does not exist'));
            $this->_redirect('*/*/');
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
                $fieldsetId = $this->getRequest()->getParam('id');
                $flexibleformsId = $this->getRequest()->getParam('form_id');
                $fieldsetModel = Mage::getModel("flexibleforms/fieldset")->load($fieldsetId);

                if($fieldsetModel->getCreatedTime() == NULL)
                {
                    $created_time = now();
                } else {
                    $created_time = $fieldsetModel->getCreatedTime();
                }
                
                $fieldsetModel->setData($postData)
                            ->setFieldsetId($fieldsetId)
                            ->setFormId($flexibleformsId)
                            ->setCreatedTime($created_time)
                            ->setUpdateTime(now())
                            ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The field has been saved successfully.'));
                Mage::getSingleton('adminhtml/session')->setFlexibleformsData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $fieldsetModel->getId(), 'form_id' => $fieldsetModel->getFormId()));
                    return;
                }
                $this->_redirect('*/flexibleforms_flexibleforms/edit', array('id' => $flexibleformsId,'active_tab' =>'fieldset_section'));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFlexibleformsData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $fieldsetId, 'form_id' => $flexibleformsId));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Unexpected error'));
        $this->_redirect('flexibleforms_flexibleforms/edit');
    }

    // Delete Action for field edit page
    public function deleteAction()
    {
        if( $fieldsetId = $this->getRequest()->getParam('id') > 0){
            try{
                // Update "fieldset_id" value = 0 into "flexibleforms_fields" table when delete fieldset
                $fieldsModel = Mage::getModel("flexibleforms/fields");
                $fields_collection = Mage::getModel('flexibleforms/fields')->getCollection()->addFieldToFilter('fieldset_id', $fieldsetId);
                foreach ( $fields_collection as $fields){
                    $fieldsModel->setData()
                                ->setFieldId($fields->getFieldId())
                                ->setFieldsetId(0)
                                ->save();
                }

                // Delete fieldset by its Id
                $fieldset = Mage::getModel('flexibleforms/fieldset')->load($this->getRequest()->getParam('id'));
                $form_id = $fieldset->getFormId();
                $fieldset->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The fieldset has been deleted successfully.'));
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirectReferer();
            }
        }
        $this->_redirect('*/flexibleforms_flexibleforms/edit',array('id' => $form_id, 'active_tab' =>'fieldset_section'));
    }

    // Mass Delete Action for fields Grid
    public function massDeleteAction()
    {
        $fieldsetIds = $this->getRequest()->getParam('id');
        if(!is_array($fieldsetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $fieldsModel = Mage::getModel("flexibleforms/fields");
                foreach ($fieldsetIds as $fieldsetId) {
                    // Update "fieldset_id" value = 0 into "flexibleforms_fields" table when delete fieldset
                    $fields_collection = Mage::getModel('flexibleforms/fields')->getCollection()->addFieldToFilter('fieldset_id', $fieldsetId);
                    foreach ( $fields_collection as $fields){
                        $fieldsModel->setData()
                                    ->setFieldId($fields->getFieldId())
                                    ->setFieldsetId(0)
                                    ->save();
                    }

                    // Delete fieldset by its Id
                    $fieldset = Mage::getModel('flexibleforms/fieldset')->load($fieldsetId);
                    $fieldset->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($fieldsetIds)
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
                foreach ($flexibleformsIds as $flexibleformsId) {
                    $flexibleforms = Mage::getSingleton('flexibleforms/fieldset')
                        ->load($flexibleformsId)
                        ->setFieldsetStatus($this->getRequest()->getParam('fieldset_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($flexibleformsIds))
                );
            } catch (Exception $e) {
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
}
?>