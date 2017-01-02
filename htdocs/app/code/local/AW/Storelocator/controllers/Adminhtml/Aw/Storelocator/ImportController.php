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

class AW_Storelocator_Adminhtml_Aw_Storelocator_ImportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Import'))
            ->loadLayout()
            ->_setActiveMenu('cms')
            ->renderLayout();
    }

    public function proceedImportAction()
    {
        $_redirect = '*';
        if ($this->getRequest()->isPost() && !empty($_FILES['import_file']['tmp_name'])) {

            try {
                $fileName = $_FILES['import_file']['tmp_name'];
                $csvObject = new Varien_File_Csv();
                $csvData = $csvObject->getData($fileName);

                switch ($this->getRequest()->getPost('import_type')) {
                    case "hours":
                        $this->_importHours($csvData);
                        $_redirect = 'aw_storelocator_hour';
                        break;
                    case "locations":
                        $this->_importLocations($csvData);
                        $_redirect = 'aw_storelocator_location';
                        break;
                }

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The items have been imported.'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid file upload attempt'));
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/' . $_redirect . '/index');
    }

    protected function _importLocations($csvData)
    {
        if ($this->getRequest()->getPost('is_overwrite')) {
            Mage::getResourceModel('aw_storelocator/location')->cleanLocationsTableBeforeImport();
        }

        for ($i = 1; $i < count($csvData); $i++) {
            if (!$csvData[$i][1]) continue; // if exist required title

            $locationModel = Mage::getModel('aw_storelocator/location');
            $locationData = array(
                'status' => $csvData[$i][1],
                'store_ids' => $csvData[$i][2],
                'title' => $csvData[$i][3],
                'description' => $csvData[$i][4],
                'priority' => $csvData[$i][5],
                'country' => $csvData[$i][6],
                'state' => $csvData[$i][7],
                'city' => $csvData[$i][8],
                'street' => $csvData[$i][9],
                'zip' => $csvData[$i][10],
                'phone' => $csvData[$i][11],
                'zoom' => $csvData[$i][12],
                'latitude' => $csvData[$i][13],
                'longtitude' => $csvData[$i][14],
                'image' => $csvData[$i][15],
                'custom_marker' => $csvData[$i][16]
            );

            foreach ($locationData as $dataName => $dataValue) {
                $locationModel->setData($dataName, $dataValue);
            }

            $locationModel->save();
        }
    }

    protected function _importHours($csvData)
    {
        if ($this->getRequest()->getPost('is_overwrite')) {
            Mage::getResourceModel('aw_storelocator/hour')->cleanHoursTableBeforeImport();
        }

        for ($i = 1; $i < count($csvData); $i++) {
            if (!$csvData[$i][1]) continue; // if exist required title

            $hourModel = Mage::getModel('aw_storelocator/hour');
            $hourData = array(
                'title' => $csvData[$i][1],
                'monday_is_open' => $csvData[$i][2],
                'monday_opening_times' => $csvData[$i][3],
                'monday_closing_times' => $csvData[$i][4],
                'tuesday_is_open' => $csvData[$i][5],
                'tuesday_opening_times' => $csvData[$i][6],
                'tuesday_closing_times' => $csvData[$i][7],
                'wednesday_is_open' => $csvData[$i][8],
                'wednesday_opening_times' => $csvData[$i][9],
                'wednesday_closing_times' => $csvData[$i][10],
                'thursday_is_open' => $csvData[$i][11],
                'thursday_opening_times' => $csvData[$i][12],
                'thursday_closing_times' => $csvData[$i][13],
                'friday_is_open' => $csvData[$i][14],
                'friday_opening_times' => $csvData[$i][15],
                'friday_closing_times' => $csvData[$i][16],
                'saturday_is_open' => $csvData[$i][17],
                'saturday_opening_times' => $csvData[$i][18],
                'saturday_closing_times' => $csvData[$i][19],
                'sunday_is_open' => $csvData[$i][20],
                'sunday_opening_times' => $csvData[$i][21],
                'sunday_closing_times' => $csvData[$i][22],
                'status' => $csvData[$i][23]
            );

            $location_ids = $csvData[$i][24];

            if ($location_ids != '' && !is_null($location_ids)) {
                $hourData['location_ids'] = explode(',', $location_ids);
            }

            foreach ($hourData as $dataName => $dataValue) {
                $hourModel->setData($dataName, $dataValue);
            }
            $hourModel->save();
        }
    }
}