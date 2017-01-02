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
 * @package    AW_Storelocator
 * @version    1.1.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Storelocator_Adminhtml_Aw_Storelocator_LocationController extends Mage_Adminhtml_Controller_Action
{

    protected function displayTitle($data)
    {
        if (version_compare(Mage::getVersion(), '1.4', '>=')) {
            $this->_title($this->__('Store Locator'));
            foreach ((array)$data as $title) {
                $this->_title($this->__($title));
            }
        }
        return $this;
    }

    public function indexAction()
    {
        $this
            ->displayTitle('Manage Locations')
            ->loadLayout()
            ->_setActiveMenu('cms')
            ->renderLayout()
        ;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $location = Mage::getModel('aw_storelocator/location')->load($this->getRequest()->getParam('id', false));
        Mage::register('aw_storelocator_location', $location);
        if ($location->getId()) {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('Edit Location');
            $this->displayTitle('Edit Location');
        } else {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('New Location');
            $this->displayTitle('New Location');
        }

        $content = $this->getLayout()->createBlock('aw_storelocator/adminhtml_location_edit')
            ->setData(
                'form_action_url', $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id')))
            )
        ;
        $this
            ->loadLayout()
            ->_setActiveMenu('cms')
            ->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle)
            ->_addContent($content)
            ->_addLeft($this->getLayout()->createBlock('aw_storelocator/adminhtml_location_edit_tabs'))
            ->renderLayout();
    }

    public function saveAction()
    {
        $request = new Varien_Object($this->getRequest()->getParams());
        try {

            $location = Mage::getModel('aw_storelocator/location')
                ->load($request->getId())
                ->addData($request->getData())
                ->save()
            ;
            $this->_uploadFiles($location, $request);
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Location successfully saved'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        if ($request->getBack()) {
            $id = isset($location)?$location->getId():$request->getId();
            return $this->_redirect('*/*/edit', array('id' => $id, 'tab' => $request->getTab()));
        }
        return $this->_redirect('*/*/');
    }

    protected function _uploadFiles($location, $request)
    {
        $uploadPath = Mage::getBaseDir('media') . DS . 'aw_storelocator' . DS . $location->getId() . DS . 'store';
        if ($request->getData('image/delete')) {
            $this->_clearAll($uploadPath);
            $location->setImage(null);
        }
        if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $this->_clearAll($uploadPath);
            $uploader = new Varien_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->save($uploadPath, @$_FILES['image']['name']);
            $location->setImage($uploader->getUploadedFileName());
        } else {
            $this->_throwUploadError($_FILES['image']['error']);
        }

        $uploadPath = Mage::getBaseDir('media') . DS . 'aw_storelocator' . DS . $location->getId() . DS . 'marker';
        if ($request->getData('custom_marker/delete')) {
            $this->_clearAll($uploadPath);
            $location->setCustomMarker(null);
        }
        if ($_FILES['custom_marker']['error'] == UPLOAD_ERR_OK) {
            $this->_clearAll($uploadPath);
            $uploader = new Varien_File_Uploader('custom_marker');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->save($uploadPath, @$_FILES['custom_marker']['name']);
            $location->setCustomMarker($uploader->getUploadedFileName());
        } else {
            $this->_throwUploadError($_FILES['custom_marker']['error']);
        }
        $location->save();
    }

    protected function _clearAll($path)
    {
        foreach (glob("$path/*") as $img) {
            @unlink($img);
        }
    }

    protected function _removeScope($folder)
    {
        $uploadDir = Mage::helper('aw_storelocator')->getUploadDir(array('folder' => $folder));
        $this->_clearAll($uploadDir . DS . 'marker');
        $this->_clearAll($uploadDir . DS . 'store');
        @rmdir($uploadDir . DS . 'marker');
        @rmdir($uploadDir . DS . 'store');
        @rmdir($uploadDir);
    }

    protected function _throwUploadError($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception(
                    $this->__('The uploaded file exceeds the upload_max_filesize directive in php.ini')
                );
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception(
                    $this->__(
                        'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'
                    )
                );
            case UPLOAD_ERR_PARTIAL:
                throw new Exception($this->__('The uploaded file was only partially uploaded'));
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new Exception($this->__('Missing a temporary folder'));
            case UPLOAD_ERR_CANT_WRITE:
                throw new Exception($this->__('Failed to write file to disk'));
            default:
                break;
        }
    }

    public function exportCsvAction()
    {
        $fileName = 'locations.csv';
        $content = $this->getLayout()->createBlock('aw_storelocator/adminhtml_location_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName = 'locations.xml';
        $content = $this->getLayout()->createBlock('aw_storelocator/adminhtml_location_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function deleteAction()
    {
        try {
            $request = $this->getRequest()->getParams();
            if (!isset($request['id'])) {
                throw new Mage_Core_Exception($this->__('Incorrect location'));
            }
            Mage::getModel('aw_storelocator/location')->setId($request['id'])->delete();
            $this->_removeScope($request['id']);
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Location successfully deleted'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        try {
            $ruleIds = $this->getRequest()->getParam('locations');
            if (!is_array($ruleIds)) {
                throw new Mage_Core_Exception($this->__('Invalid location ids'));
            }

            foreach ($ruleIds as $rule) {
                Mage::getSingleton('aw_storelocator/location')->setId($rule)->delete();
                $this->_removeScope($rule);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d location(s) have been successfully deleted', count($ruleIds))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/aw_storelocator/location');
    }

}