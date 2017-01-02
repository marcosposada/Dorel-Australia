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

class AW_Storelocator_Block_Adminhtml_Hour_Edit_Tab_Location extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('AwStorelocatorHourGrid');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(false);
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_locations') {
            $locationIds = $this->getSelectedLocations();

            if (empty($locationIds)) {
                $locationIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('location_id', array('in' => $locationIds));
            } else {
                if($locationIds) {
                    $this->getCollection()->addFieldToFilter('location_id', array('nin' => $locationIds));
                }
            }
        } elseif($column->getId() == 'assigned') {
            if ($column->getFilter()->getValue() == 'unassigned') {
                $this->getCollection()->addUnassignedFilter();
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('aw_storelocator/location')->getCollection()->addHours();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('in_locations', array(
            'header'    => $this->__(''),
            'header_css_class'=>'a-center',
            'type'      => 'checkbox',
            'index'     => 'location_id',
            'align'     => 'center',
            'field_name'=> 'in_locations',
            'values'    => $this->getSelectedLocations(),
        ));

        $this->addColumn('name', array(
            'header'    => $this->__('Title'),
            'index'     => 'location_title',
            'align'     => 'left',
        ));

        $this->addColumn('country', array(
            'header'    => $this->__('Country'),
            'index'     => 'country',
            'align'     => 'left',
        ));

        $this->addColumn('city', array(
            'header'    => $this->__('City'),
            'index'     => 'city',
            'align'     => 'left',
        ));

        $this->addColumn('street', array(
            'header'    => $this->__('Street'),
            'index'     => 'street',
            'align'     => 'left',
        ));

        $this->addColumn('zip', array(
            'header'    => $this->__('Zip'),
            'index'     => 'zip',
            'align'     => 'left',
        ));

        $this->addColumn('assigned', array(
            'header'    => $this->__('Assigned to Store Location'),
            'index'     => 'assigned',
            'type'    => 'options',
            'align'     => 'left',
            'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_assigned',
            'options'    => Mage::getModel('aw_storelocator/source_assigned')->toOptionArray(),
        ));

        return parent::_prepareColumns();
    }

    public function getSelectedLocationHidden()
    {
        return Mage::registry('aw_storelocator_hour')->getLocationIds();
    }

    public function getRowUrl($row)
    {
        return '#';
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/locationGrid', array('_current' => true));
    }
}