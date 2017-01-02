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

class Pixlogix_Flexibleforms_Adminhtml_Flexibleforms_ResultController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('flexibleforms/result');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('Manage Forms'));
        $this->_title($this->__('View Result'));
        $this->renderLayout();
    }

    /*
     * To set Results Grid
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('flexibleforms/adminhtml_result_grid')->toHtml()
        );
    }

    /*
     * To massDeleteAction for delete records from Results Grid
     */
    public function massDeleteAction()
    {
        $flexibleformsResultIds = $this->getRequest()->getParam('flexibleforms_result');
        if(!is_array($flexibleformsResultIds))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        }
        else
        {
            try
            {
                foreach ($flexibleformsResultIds as $flexibleformsresultId)
                {
                    $flexibleformsresult = Mage::getModel('flexibleforms/result')->load($flexibleformsresultId);
                    $formId = $flexibleformsresult->getFormId();
                    // To remove file and images
                    /*$serializeValue = $flexibleformsresult->getValue();
                    $removeFiles = Mage::getModel('flexibleforms/result')->removeFiles($formId,$serializeValue);
                     * 
                     */
                    $removeFiles = Mage::getModel('flexibleforms/flexibleforms')->removeFilesByForm($formId);
                    $flexibleformsresult->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($flexibleformsResultIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index',array('form_id'=>$formId));
    }

    public function exportCsvAction() {
        $fileName = 'flexibleforms_result.csv';
        $content    = $this->getLayout()->createBlock('flexibleforms/adminhtml_result_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName   = 'flexibleforms_result.xls';
        $content    = $this->getLayout()->createBlock('flexibleforms/adminhtml_result_grid')
                ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    //To force download file from admin result grid page
    protected function downloadAction()
    {
        $resultId = $this->getRequest()->getParam('result_id');
        $fieldId = $this->getRequest()->getParam('field_id');
        $resultCollection = Mage::getModel('flexibleforms/result')->load($resultId);
        $unserializeResult = unserialize($resultCollection->getValue());
        $filepath = Mage::getBaseUrl("media") .'flexibleforms/files/'.$unserializeResult[$fieldId];
        $this->getResponse ()
            ->setHttpResponseCode ( 200 )
            ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
            ->setHeader ( 'Pragma', 'public', true )
            ->setHeader ( 'Content-type', 'application/force-download' )
            ->setHeader ( 'Content-Disposition', 'attachment' . '; filename=' . basename($filepath) );
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile ( $filepath );
        exit;	
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
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

    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }
}
?>