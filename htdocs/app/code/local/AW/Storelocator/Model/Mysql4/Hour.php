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

class AW_Storelocator_Model_Mysql4_Hour extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('aw_storelocator/opening_hours', 'hour_id');
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::_afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('aw_storelocator/opening_hours_location'), 'location_id')
            ->where($this->getIdFieldName() . ' = ?', $object->getId());

        if ($locationsArray = $this->_getReadAdapter()->fetchCol($select)) {
            $object->setData('location_ids', $locationsArray);
        } else {
            $object->setData('location_ids', array());
        }

        return $this;
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);

        foreach (AW_Storelocator_Helper_Config::$WEEK_DAYS as $day) {
            $openingTimeFieldname = $day.'_opening_times';
            if (is_array($object->getData($openingTimeFieldname))) {
                $object->setData($openingTimeFieldname, implode(':', $object->getData($openingTimeFieldname)));
            }

            $closingTimeFieldname = $day.'_closing_times';
            if (is_array($object->getData($closingTimeFieldname))) {
                $object->setData($closingTimeFieldname, implode(':', $object->getData($closingTimeFieldname)));
            }
        }
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        $condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());

        if ($object->hasData('location_ids')) {
            $locations = $object->getData('location_ids');
        } else {
            return $this;
        }

        if (!is_array($locations)){
            $locations = array();
        }

        $this->_getWriteAdapter()->delete($this->getTable('aw_storelocator/opening_hours_location'), $condition);

        foreach ($locations as $location) {
            $select = $this->_getReadAdapter()
                ->select()
                ->from($this->getTable('aw_storelocator/location'), array('count' => 'COUNT(*)'))
                ->where('location_id IN(?)', $location);
            $checkLocationExist = $this->_getReadAdapter()->fetchOne($select);

            if ($checkLocationExist) {
                $this->_getWriteAdapter()->insert(
                    $this->getTable('aw_storelocator/opening_hours_location'),
                    array(
                        $this->getIdFieldName() => $object->getId(),
                        'location_id'        => $location
                    )
                );
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('aw_storelocator')->__('Store Location with ID#' . $location . ' assigned to Openning Hour `' . $object->getTitle() . '` is not exist. This relation has not been imported.'));
            }
        }

        return $this;
    }

    public function cleanHoursTableBeforeImport()
    {
        $collection = Mage::getModel('aw_storelocator/hour')->getCollection();
        foreach ($collection as $item) {
            $item->delete();
        }
    }
}