<?php
class Pixlogix_Flexibleforms_Adminhtml_Flexibleforms_FlexibleformsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('flexibleforms/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Forms'), Mage::helper('adminhtml')->__('Manage Forms'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('Manage Forms'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $flexibleformsId     = $this->getRequest()->getParam('id');
        $flexibleformsModel  = Mage::getModel('flexibleforms/flexibleforms')->load($flexibleformsId);
        $this->_title( $flexibleformsModel->getId() ? $this->__('Edit \'%s\' Form', $flexibleformsModel->getFormTitle()) : $this->__('Add Form') );

        if ($flexibleformsModel->getId() || $flexibleformsId == 0)
        {
            Mage::register('flexibleforms_data', $flexibleformsModel);

            $this->loadLayout();
            $this->_setActiveMenu('flexibleforms/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Forms'), Mage::helper('adminhtml')->__('Manage Forms'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Form Item'), Mage::helper('adminhtml')->__('Form Item'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit'))
                 ->_addLeft($this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Form does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {

            try {

                $postData = $this->getRequest()->getPost();
                if(!in_array(0,$postData['stores']))
                {
                    $stores = implode(',',$postData['stores']);
                }
                else
                {
                    $allStores = Mage::app()->getStores();
                    $arrStore = array(0);
                    foreach ($allStores as $_eachStoreId => $val)
                    {
                        $storeId = 0;
                        $_storeId = Mage::app()->getStore($_eachStoreId)->getId();
                        $arrStore[] = $_storeId;
                    }
                    $stores = implode(',',$arrStore);
                }
                $id = $this->getRequest()->getParam("id");
                $flexibleformsModel = Mage::getModel("flexibleforms/flexibleforms")->load($id);

                if(empty($postData['form_url_key']))
                {
                    $postData['form_url_key'] = Mage::getSingleton('catalog/product')->formatUrlKey($postData['form_title']);
                }
                else
                {
                    $postData['form_url_key']=trim(str_replace(' ','-',$postData['form_url_key']));
                }

                $_formCollection = Mage::getModel('flexibleforms/flexibleforms')
                                    ->getCollection()
                                    ->addFieldToSelect('*')
                                    ->addFieldToFilter('form_url_key', array('like' => $postData['form_url_key']));

                if($this->getRequest()->getParam('id')>0)
                {
                    $_formCollection->addFieldToFilter('form_id', array('neq' => $this->getRequest()->getParam('id')));
                }

                $forms = $_formCollection->count();
                if($forms > 0)
                {
                    $err_msg = $this->__('Form Url Key must be unique');
                    Mage::getSingleton('adminhtml/session')->addError($err_msg);
                    if($this->getRequest()->getParam('id'))
                    {
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    } else {
                        $this->_redirect('*/*/');
                        return;
                    }
                }

                if($flexibleformsModel->getCreatedTime() == '')
                {
                    $created_time = now();
                } else {
                    $created_time = $flexibleformsModel->getFormCreatedTime();
                }
                $postData['admin_email_address']  = ($postData['enable_admin_email']==1) ? $postData['admin_email_address'] : '';
                $postData['customer_reply_email'] = ($postData['enable_customer_email']==1) ? $postData['customer_reply_email'] : '';

                if(!empty($postData['form_redirect_url']))
                {
                    $flag = 0;
                    if(preg_match("/^http:\/\//", $postData['form_redirect_url']) || preg_match("/^https:\/\//", $postData['form_redirect_url']) )
                    {
                        $flag = 1;
                    }

                    if($flag==0){
                        $http = $_SERVER['REQUEST_SCHEME'] ? $_SERVER['REQUEST_SCHEME'].'://' : 'http://';
                        $postData['form_redirect_url'] = $http.$postData['form_redirect_url'];
                    }
                }
                $flexibleformsModel->setData($postData)
                        ->setId($this->getRequest()->getParam('id'))
                        ->setFormUrlKey($postData['form_url_key'])
                        ->setStoreId($stores)
                        ->setFormCreatedTime($created_time)
                        ->setFormUpdateTime(now())
                        ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The form has been saved successfully.'));
                Mage::getSingleton('adminhtml/session')->setFlexibleformsData(false);
                if ($this->getRequest()->getParam('back')) {
                    $active_tab = $this->getRequest()->getParam('active_tab');
                    $this->_redirect('*/*/edit', array('id' => $flexibleformsModel->getId(),'active_tab' => $active_tab));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFlexibleformsData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleforms')->__('Unable to find form to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $flexibleformsModel = Mage::getModel('flexibleforms/flexibleforms');
                $formId = $this->getRequest()->getParam('id');
                //To remove image and file from folder
                $removeFiles = Mage::getModel('flexibleforms/flexibleforms')->removeFilesByForm($formId);
                $flexibleformsModel->setId($this->getRequest()->getParam('id'))->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The form has been deleted successfully.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_grid')->toHtml()
        );
    }

    public function massDeleteAction() {
        $flexibleformsIds = $this->getRequest()->getParam('flexibleforms');
        if(!is_array($flexibleformsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexibleformsIds as $flexibleformsId) {
                    $flexibleforms = Mage::getModel('flexibleforms/flexibleforms')->load($flexibleformsId);
                    $removeFiles = Mage::getModel('flexibleforms/flexibleforms')->removeFilesByForm($flexibleformsId);
                    $flexibleforms->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($flexibleformsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $flexibleformsIds = $this->getRequest()->getParam('flexibleforms');
        if(!is_array($flexibleformsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexibleformsIds as $flexibleformsId) {
                    $flexibleforms = Mage::getSingleton('flexibleforms/flexibleforms')
                        ->load($flexibleformsId)
                        ->setFormStatus($this->getRequest()->getParam('form_status'))
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
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'flexibleforms.csv';
        $content  = $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'flexibleforms.xml';
        $content  = $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_grid')
             ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName = 'flexibleforms.xls';
        $content  = $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    public function fieldslistAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tab_fields')->toHtml()
        );
    }
    public function fieldsetlistAction()
    {
        
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('flexibleforms/adminhtml_flexibleforms_edit_tab_fieldset')->toHtml()
        );
    }

    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }
}