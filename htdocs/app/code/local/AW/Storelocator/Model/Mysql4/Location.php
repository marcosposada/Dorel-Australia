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


class AW_Storelocator_Model_Mysql4_Location extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('aw_storelocator/location', 'location_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (is_array($object->getStoreIds())) {
            $object->setStoreIds(implode(',', $object->getStoreIds()));
        }
        if (is_array($object->getImage())) {
            $object->unsImage();
        }
        if (is_array($object->getCustomMarker())) {
            $object->unsCustomMarker();
        }
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!is_null($object->getStoreIds())) {
            $object->setStoreIds(explode(',', $object->getStoreIds()));
        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if (!is_null($object->getStoreIds())) {
            $object->setStoreIds(explode(',', $object->getStoreIds()));
        }
        if (!is_null($object->getImage())) {
            $object->setImage(
                Mage::helper('aw_storelocator')->getUploadImagePath(
                    array('name' => $object->getImage(), 'folder' => $object->getId(), 'type' => 'store')
                )
            );
        }
        if (!is_null($object->getCustomMarker())) {
            $object->setCustomMarker(
                Mage::helper('aw_storelocator')->getUploadImagePath(
                    array('name' => $object->getCustomMarker(), 'folder' => $object->getId(), 'type' => 'marker')
                )
            );
        }
    }

    public function getCountAssignedHours($location, $hour)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('aw_storelocator/opening_hours_location'), array('count' => 'COUNT(*)'))
            ->where('hour_id NOT IN(?)', $hour)
            ->where('location_id IN(?)', $location);

        $count = $this->_getReadAdapter()->fetchOne($select);
        return $count;
    }

    public function cleanLocationsTableBeforeImport()
    {
        $collection = Mage::getModel('aw_storelocator/location')->getCollection();
        foreach ($collection as $item) {
            $item->delete();
        }
    }
}