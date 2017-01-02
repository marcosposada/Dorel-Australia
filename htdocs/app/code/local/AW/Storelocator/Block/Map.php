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


class AW_Storelocator_Block_Map extends Mage_Core_Block_Template
{
    public function getLocations()
    {
        if (!$this->getData('locations')) {
            $collection = Mage::getModel('aw_storelocator/location')->getCollection()
                ->addHours()
                ->addEnabledFilter()
                ->addPriorityOrder()
                ->addStoreFilter(Mage::app()->getStore()->getId())
            ;

            $radius = $this->getRequest()->getParam('radius');
            if (isset($radius)) {
                $collection->addRadiusFilter($this->getRequest()->getParam('radius'));
            }
            $country = $this->getRequest()->getParam('country');
            if (!empty($country)) {
                $collection->addCountryFilter($this->getRequest()->getParam('country'));
            }
            $state = $this->getRequest()->getParam('state');
            if (!empty($state)) {
                $collection->addStateFilter($this->getRequest()->getParam('state'));
            }
            $city = $this->getRequest()->getParam('city');
            if (!empty($city)) {
                $collection->addCityFilter($this->getRequest()->getParam('city'));
            }
            $zip = $this->getRequest()->getParam('zip');
            if (!empty($zip)) {
                $collection->addZipFilter($this->getRequest()->getParam('zip'));
            }

            $this->setData('locations', $collection);
        }

        return $this->getData('locations');
    }

    public function getLocationsJson()
    {
        $locations = array();
        $locations['items'] = array();
        foreach ($this->getLocations() as $item) {
            $location = $item->getData();
//            $location['country'] = $this->getCountryName($location['country']);
//            $location['state'] = $this->getStateName($location['state']);
            $location['url'] = $this->getStoreImgUrl($item);
            $location['hours'] = $this->getHours($item);
            $locations['items'][] = $location;
        }
        return Zend_Json::encode($locations);
    }

    public function getRegions()
    {
        if (!$this->getData('country_regions')) {
            $regions = array();
            foreach ($this->getLocations() as $location) {
                if ((int)$location->getState()) {
                    $regions[] = $location->getState();
                }
            }

            if (empty($regions)) {
                $collection = new Varien_Data_Collection();
                $this->setData('country_regions', $collection->toArray());
            } else {
                $collection = Mage::getModel('directory/region')->getCollection();
                $countryRegions = $collection
                    ->addFieldToFilter("{$this->_getCorrelationName($collection)}.region_id", array('in' => $regions))
                    ->toArray()
                ;
                $this->setData('country_regions', $countryRegions);
            }
        }
        return $this->getData('country_regions');
    }

    public function renderCmsBlock()
    {
        $block = $this->helper('aw_storelocator/config')->getCmsBlock(Mage::app()->getStore()->getId());
        if ($block) {
            return $this->getLayout()->createBlock('cms/block')->setBlockId($block)->toHtml();
        }
    }

    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                    'home', array('label' => $this->__('Home'), 'title' => $this->__('Home'), 'link' => Mage::getBaseUrl())
                );
            $breadcrumbsBlock->addCrumb(
                    'category', array('label' => $this->__('Store Locations'), 'title' => $this->__('Store Locations'))
                );
        }

        $head = $this->getLayout()->getBlock('head');
        if ($head) {
            $head->setTitle($this->__('Store Locations'));
        }
        return parent::_prepareLayout();
    }

    private function _getCorrelationName($collection)
    {
        list($correlationName) = ($collection->getSelect()->getPart(Zend_Db_Select::COLUMNS));
        return array_shift($correlationName);
    }

    public function getCountries()
    {
        if (!$this->getData('countries')) {
            $this->setData('countries', Mage::getSingleton('aw_storelocator/source_country')->toFlatArray());
        }
        return $this->getData('countries');
    }

    public function getCountryName($code)
    {
        $countries = $this->getCountries();
        return @$countries[$code];
    }

    public function getStateName($code)
    {
        $regions = $this->getRegions();
        foreach ($regions['items'] as $item) {
            if ($item['region_id'] == $code) {
                return $item['code'];
            }
        }
        return $code;
    }

    public function getStoreImgUrl($location)
    {
        if ($location->getImage()) {
            return $this->helper('aw_storelocator')->getUploadImagePath(
                array(
                     'folder' => $location->getId(),
                     'type'   => 'store',
                     'name'   => $location->getImage()
                )
            );
        }
        return null;
    }

    public function getAddress()
    {
        return $this->getRequest()->getParam('address');
    }

    public function getCountry()
    {
        return $this->getRequest()->getParam('country');
    }

    public function getState()
    {
        return $this->getRequest()->getParam('state');
    }

    public function getCity()
    {
        return $this->getRequest()->getParam('city');
    }

    public function getZip()
    {
        return $this->getRequest()->getParam('zip');
    }

    public function getLatitude()
    {
        return Mage::helper('aw_storelocator')->getLatLng('latitude');
    }

    public function getLongitude()
    {
        return Mage::helper('aw_storelocator')->getLatLng('longitude');
    }

    public function getRadiusSelectElement()
    {
        $options = array(
            array('value' => '',  'label' => $this->__('Everywhere'))
        );

        $radius = explode(',', Mage::getStoreConfig('aw_storelocator/search/radius'));

        if (!is_array($radius)) return false;

        foreach ($radius as $option) {
            $options[] = array('value'  => $option, 'label' => $option);
        }

        $value = Mage::helper('aw_storelocator')->getRadius();
        return $this->_getSelectBlock()
            ->setName('radius')
            ->setId('aw-storelocator-search-block-radius')
            ->setValue($value)
            ->setOptions($options)
            ->setClass('select')
            ->getHtml();
    }

    public function getMeasurementSelectElement()
    {
        $options = array(
            array('value' => 'km', 'label' => $this->__('km')),
            array('value' => 'mi', 'label' => $this->__('mi'))
        );

        $value = Mage::helper('aw_storelocator')->getMeasurement();
        return $this->_getSelectBlock()
            ->setName('measurement')
            ->setId('aw-storelocator-search-block-measurement')
            ->setValue($value)
            ->setOptions($options)
            ->getHtml();
    }

    public function getCountriesSelectElement()
    {
        $options = array(
            array('value' => '',  'label' => $this->__('Select Country'))
        );

        $countries = $this->getCountries();
        foreach ($countries as $value => $name) {
            $options[] = array('value'  => $value, 'label' => $name);
        }

        $value = $this->getCountry();
        return $this->_getSelectBlock()
            ->setName('country')
            ->setId('country')
            ->setValue($value)
            ->setOptions($options)
            ->getHtml();
    }

    protected function _getSelectBlock()
    {
        $block = $this->getData('_select_block');
        if (is_null($block)) {
            $block = $this->getLayout()->createBlock('core/html_select');
            $this->setData('_select_block', $block);
        }
        return $block;
    }

    public function getHours($location)
    {
        $hours = '';
        if (!$location->getHourId()) return $hours;

        foreach (AW_Storelocator_Helper_Config::$WEEK_DAYS as $day) {
            $hours .= $location[$day.'_is_open'] ? ' ' . ucfirst($day) .' (' . date('H:i',strtotime($location[$day.'_opening_times'])) . ' - ' . date('H:i',strtotime($location[$day.'_closing_times'])) . ')<br />': ' ' . ucfirst($day) . ' (' . $this->__('Closed') . ')<br />';
        }
        return $hours;
    }

    public function getTab()
    {
        return $this->getRequest()->getParam('tab');
    }

    public function getBaseJson()
    {
        $storeImgTpl = $this->helper('aw_storelocator')->getUploadImagePath(
            array(
                 'folder' => '{{id}}',
                 'type'   => 'store',
                 'name'   => '{{name}}'
            )
        );

        $markerImgTpl = $this->helper('aw_storelocator')->getUploadImagePath(
            array(
                 'folder' => '{{id}}',
                 'type'   => 'marker',
                 'name'   => '{{name}}'
            )
        );

        return Zend_Json::encode(
            array(
                 'media_url'      => Mage::getBaseUrl('media'),
                 'countries'      => $this->getCountries(),
                 'regions'        => $this->getRegions(),
                 'store_img_tpl'  => $storeImgTpl,
                 'marker_img_tpl' => $markerImgTpl)
        );
    }
}