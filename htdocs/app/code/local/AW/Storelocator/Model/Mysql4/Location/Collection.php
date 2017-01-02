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


class AW_Storelocator_Model_Mysql4_Location_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('aw_storelocator/location');
    }

    protected function _joinHoursTable()
    {
        $this->getSelect()
            ->joinLeft(
                array('hours_location' => $this->getTable('aw_storelocator/opening_hours_location')),
                'main_table.location_id = hours_location.location_id',
                array('location_title' => 'main_table.title'))
            ->joinLeft(
                array('hours' => $this->getTable('aw_storelocator/opening_hours')),
                'hours_location.hour_id = hours.hour_id',
                array('hours.*', 'hours_title' => 'hours.title'))
            ->group('main_table.location_id');
//        echo $this->getSelect();
//        die();
        return $this;
    }

    public function addUnassignedFilter()
    {
        $this->getSelect()->where('hours.hour_id IS NULL');
        return $this;
    }

    public function addCountryFilter($country)
    {
        $this->getSelect()->where('main_table.country = ?', $country);
        return $this;
    }

    public function addStateFilter($state)
    {
        $this->getSelect()->where('main_table.state = ?', $state);
        return $this;
    }

    public function addCityFilter($city)
    {
        $this->getSelect()->where('main_table.city = ?', $city);
        return $this;
    }

    public function addZipFilter($zip)
    {
        $this->getSelect()->where('main_table.zip = ?', $zip);
        return $this;
    }

    public function addEnabledFilter()
    {
        $this->getSelect()->where('main_table.status = ?', 1);
        return $this;
    }

    public function addPriorityOrder()
    {
        $this->getSelect()->order('main_table.priority DESC');
        return $this;
    }

    public function addStoreFilter($store)
    {
        $this->getSelect()->where('find_in_set(0, main_table.store_ids) or find_in_set(?, main_table.store_ids)', $store);
        return $this;
    }

    public function addHours()
    {
        $this->_joinHoursTable();

        return $this;
    }

    public function addRadiusFilter($radius)
    {
        if (null === $radius) {
            return $this;
        }

        $radius = (int)$radius;

        if ($radius == 0) return $this;

        $measurement = Mage::helper('aw_storelocator')->getMeasurement();
        $ratio = Mage::helper('aw_storelocator')->getMeasurementRatio($measurement);

        $latitude = Mage::helper('aw_storelocator')->getLatLng('latitude');
        $longitude = Mage::helper('aw_storelocator')->getLatLng('longitude');

        $this->getSelect()
             ->columns('( ' . $ratio . ' * acos( cos( radians(' . $latitude . ') ) * cos( radians(main_table.latitude) ) * cos( radians(main_table.longtitude) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians(main_table.latitude) ) ) ) AS distance')
             ->having('distance < '. $radius);

        return $this;
    }
}