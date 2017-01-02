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

class AW_Storelocator_Adminhtml_Aw_Storelocator_HourController extends Mage_Adminhtml_Controller_Action
{
    protected function _initHour()
    {
        $hour = Mage::getModel('aw_storelocator/hour');
        $hourId = (int)$this->getRequest()->getParam('id', false);

        if ($hourId > 0) {
            $hour->load($hourId);

            if (!$hour->getId()) {
                $this->_getSession()->addError($this->__('These opening hours do not exist'));
                $this->_redirect('*/*/index');

                return false;
            }
        }

        Mage::register('aw_storelocator_hour', $hour);

        return $hour;
    }

    public function indexAction()
    {
        $this->_title($this->__('Manage Opening Hours'))
            ->loadLayout()
            ->_setActiveMenu('cms')
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $hour = $this->_initHour();
        if (!$hour) {
            return;
        }

        $this->_title($hour->getId() ? $this->__('Edit Opening Hours') : $this->__('New Opening Hours'))
            ->loadLayout()
            ->_setActiveMenu('cms')
            ->renderLayout();
    }

    public function locationAction()
    {
        if (!$hour = $this->_initHour()) {
            return;
        }

        $this->loadLayout()
            ->getLayout()
            ->getBlock('aw_storelocator.hour.tab.location')
            ->setSelectedLocations($hour->getLocationIds());

        $this->renderLayout();
    }

    public function locationGridAction()
    {
        $this->loadLayout()
            ->getLayout()
            ->getBlock('aw_storelocator.hour.tab.location')
            ->setSelectedLocations($this->getRequest()->getPost('locations', null));

        $this->renderLayout();
    }

    public function saveAction()
    {
        if (!$hour = $this->_initHour()) {
            return;
        }

        if ($post = $this->getRequest()->getPost()) {
            if ($locationIds = $this->getRequest()->getPost('location_ids', null)) {
                $post['location_ids'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($locationIds);
            }

            $locations = array();
            $error = false;
            foreach($post['location_ids'] as $locationId) {
                if(isset($post['hour_id'])) {
                    $locationCount = Mage::getResourceModel('aw_storelocator/location')->getCountAssignedHours($locationId, $post['hour_id']);
                } else {
                    $locationCount = 0;
                }

                if ($locationCount == 0) {
                    $locations[] = $locationId;
                } else {
                    $error = true;
                }
            }
            $post['location_ids'] = $locations;

            try {
                $hour->setData($post);
                $hour->save();

                if (!$error) {
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Opening Hours successfully saved'));
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('It is not allowed to assign one Store Location to different Opening Hours.'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        if ($this->getRequest()->getParam('back', null)) {
            $id = isset($hour) ? $hour->getId() : $post->getId();
            return $this->_redirect('*/*/edit', array('id' => $id));
        }

        return $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'hours.csv';
        $content = $this->getLayout()->createBlock('aw_storelocator/adminhtml_hour_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName = 'hours.xml';
        $content = $this->getLayout()->createBlock('aw_storelocator/adminhtml_hour_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function deleteAction()
    {
        try {
            $request = $this->getRequest()->getParams();
            if (!isset($request['id'])) {
                throw new Mage_Core_Exception($this->__('Incorrect Opening Hours'));
            }
            Mage::getModel('aw_storelocator/hour')->setId($request['id'])->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Opening Hours successfully deleted'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        try {
            $hourIds = $this->getRequest()->getParam('hours');
            if (!is_array($hourIds)) {
                throw new Mage_Core_Exception($this->__('Invalid Opening Hours ids'));
            }

            foreach ($hourIds as $hour) {
                Mage::getSingleton('aw_storelocator/hour')->setId($hour)->delete();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d Opening Hours(s) have been successfully deleted', count($hourIds))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/aw_storelocator/hour');
    }
} 