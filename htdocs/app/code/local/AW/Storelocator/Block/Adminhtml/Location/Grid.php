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


class AW_Storelocator_Block_Adminhtml_Location_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('AwStorelocatorGrid');
        $this->setDefaultSort('location_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('aw_storelocator/location')->getCollection());
        parent::_prepareCollection();
        $this->addAdditionalFields();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'location_id',
            array(
                 'header' => $this->__('Location Id'),
                 'align'  => 'right',
                 'width'  => '50px',
                 'index'  => 'location_id'
            )
        );

        $this->addColumn(
            'title',
            array(
                 'header' => $this->__('Title'),
                 'index'  => 'title'
            )
        );

        $this->addColumn(
            'country',
            array(
                 'header'  => $this->__('Country'),
                 'index'   => 'country',
                 'type'    => 'options',
                 'options' => Mage::getSingleton('aw_storelocator/source_country')->toFlatArray()
            )
        );

        $this->addColumn(
            'city',
            array(
                 'header' => $this->__('City'),
                 'index'  => 'city'
            )
        );

        $this->addColumn(
            'street',
            array(
                 'header' => $this->__('Street'),
                 'index'  => 'street'
            )
        );

        $this->addColumn(
            'phone',
            array(
                 'header' => $this->__('Telephone'),
                 'index'  => 'phone'
            )
        );

        $this->addColumn(
            'zip',
            array(
                 'header' => $this->__('Zip / Postal address'),
                 'index'  => 'zip'
            )
        );

        $this->addColumn(
            'description',
            array(
                 'header'   => $this->__('Description'),
                 'index'    => 'description',
                 'type'     => 'text',
                 'truncate' => 300,
                 'nl2br'    => true
            )
        );

        $this->addColumn(
            'status',
            array(
                 'header'  => $this->__('Status'),
                 'index'   => 'status',
                 'type'    => 'options',
                 'options' => array(
                     $this->__('Disabled'),
                     $this->__('Enabled')
                 ),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'store_ids',
                array(
                     'header'                    => $this->__('Store View'),
                     'index'                     => 'store_ids',
                     'type'                      => 'store',
                     'store_all'                 => true,
                     'store_view'                => true,
                     'sortable'                  => false,
                     'filter_condition_callback' => array($this, 'filterStore'),
                )
            );
        }

        $this->addColumn(
            'action',
            array(
                 'header'    => $this->__('Action'),
                 'width'     => '150px',
                 'type'      => 'action',
                 'getter'    => 'getLocationId',
                 'actions'   => array(
                     array(
                         'caption' => $this->__('Edit'),
                         'url'     => array(
                             'base' => '*/*/edit'
                         ),
                         'field'   => 'id'
                     ),
                     array(
                         'caption' => $this->__('Delete'),
                         'url'     => array(
                             'base' => '*/*/delete'
                         ),
                         'field'   => 'id',
                         'confirm' => $this->__('Are you sure?')
                     )
                 ),
                 'filter'    => false,
                 'sortable'  => false,
                 'is_system' => true
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('aw_storelocator')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('aw_storelocator')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _getExportHeaders()
    {
        $row = array();
        foreach ($this->getCollection()->getFirstItem()->getData() as $key => $value) {
            $row[] = $key;
        }
        return $row;
    }

    protected function _exportCsvItem(Varien_Object $item, Varien_Io_File $adapter)
    {
        $row = array();

        foreach ($item->getData() as $key => $value) {
            if ($key == 'store_ids') $value = implode(',', $value);
            $row[] = $value;
        }
        $adapter->streamWriteCsv($row);
    }

    protected function filterStore($collection, $column)
    {
        $val = $column->getFilter()->getValue();
        if (!$val) {
            return;
        }
        $collection->getSelect()
            ->where("FIND_IN_SET('$val', {$column->getIndex()}) OR FIND_IN_SET('0', {$column->getIndex()})")
        ;
    }

    protected function addAdditionalFields()
    {
        foreach ($this->getCollection() as $item) {
            $item->setData('store_ids', explode(',', $item->getData('store_ids')));
        }
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('location_id');
        $this->getMassactionBlock()->setFormFieldName('locations');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                 'label'   => $this->__('Delete'),
                 'url'     => $this->getUrl('*/*/massDelete'),
                 'confirm' => $this->__('Are you sure?')
            )
        );
    }

}
