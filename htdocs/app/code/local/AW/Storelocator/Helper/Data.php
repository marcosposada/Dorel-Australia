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


class AW_Storelocator_Helper_Data extends Mage_Core_Helper_Abstract
{

    const DEFAULT_LATITUDE = 40.446947;
    const DEFAULT_LONGTITUDE = -101.425781;

    const DEFAULT_KILOMETERS_RATIO = 6371;
    const DEFAULT_MILES_RATIO = 3959;

    public function getGoogleMapUrl()
    {
        $apiKey = trim(Mage::getStoreConfig('aw_storelocator/general/api_key'));

        if ($apiKey) {
            return '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.19&key=' . $apiKey
            . '&sensor=true&libraries=places"></script>';
        }
        return '<script type="text/javascript" '
        . 'src="https://maps.googleapis.com/maps/api/js?v=3.19&sensor=true&libraries=places"></script>';
    }

    public function getGoogleMapJson()
    {
        $config = array(
            'latitude'   => self::DEFAULT_LATITUDE,
            'longtitude' => self::DEFAULT_LONGTITUDE,
            'countries'  => Mage::getSingleton('aw_storelocator/source_country')->toFlatArray(),
            'noresult'   => $this->__('Nothing found'),
            'fields'     => $this->__('Please specify country, city and street address to search for location')
        );

        return Zend_Json::encode($config);
    }

    public function getUploadImagePath(array $data)
    {
        return Mage::getBaseUrl('web') . "media/aw_storelocator/{$data['folder']}/{$data['type']}/{$data['name']}";
    }

    public function getUploadDir(array $data)
    {
        $path = Mage::getBaseDir('media') . DS . 'aw_storelocator' . DS;

        if (isset($data['folder'])) {
            $path .= $data['folder'] . DS;
        }
        if (isset($data['type'])) {
            $path .= $data['type'] . DS;
        }
        if (isset($data['name'])) {
            $path .= $data['name'];
        }

        return $path;
    }

    public function getMeasurementRatio($measurement)
    {
        $ratio = AW_Storelocator_Helper_Data::DEFAULT_KILOMETERS_RATIO;
        if ($measurement == 'mi') return AW_Storelocator_Helper_Data::DEFAULT_MILES_RATIO;

        return $ratio;
    }

    public function getRadius()
    {
        if (Mage::app()->getRequest()->getParam('radius') || Mage::app()->getRequest()->getParam('radius') === '') {
            return Mage::app()->getRequest()->getParam('radius');
        }
        return Mage::getStoreConfig('aw_storelocator/search/default_radius');
    }

    public function getMeasurement()
    {
        if (Mage::app()->getRequest()->getParam('measurement')) {
            return Mage::app()->getRequest()->getParam('measurement');
        }
        return Mage::getStoreConfig('aw_storelocator/search/default_measurement');
    }

    public function getLatLng($type)
    {
        if (!($post = Mage::app()->getRequest()->getPost())) return '';

        $coordinate = Mage::app()->getRequest()->getParam($type);
        if ($coordinate) {
            return is_numeric($coordinate) ? $coordinate : $this->getDefaultLatLng($type);
        }

        return $this->getDefaultLatLng($type);
    }

    public function getDefaultLatLng($type)
    {
        if ($type == 'latitude') {
            return AW_Storelocator_Helper_Data::DEFAULT_LATITUDE;
        } else {
            return AW_Storelocator_Helper_Data::DEFAULT_LONGTITUDE;
        }

    }

    public function extensionEnabled($extensionName)
    {
        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        if (!isset($modules[$extensionName])
            || $modules[$extensionName]->descend('active')->asArray() == 'false'
            || Mage::getStoreConfig('advanced/modules_disable_output/' . $extensionName)
            || !Mage::getStoreConfig('aw_storelocator/general/enable')
        ) {
            return false;
        }
        return true;
    }

}
