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


class AW_Storelocator_Block_Adminhtml_Hour_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('AwStorelocatorHourGrid');
        $this->setDefaultSort('hour_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('aw_storelocator/hour')->getCollection();
        $this->setCollection($collection);

        $this->_isExport = false;

        return parent::_prepareCollection();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');

        parent::_afterLoadCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'hour_id',
            array(
                 'header' => $this->__('Hour Id'),
                 'align'  => 'right',
                 'width'  => '50px',
                 'index'  => 'hour_id'
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

        $this->addColumn(
            'monday',
            array(
                'header'   => $this->__('Monday'),
                'index'    => 'monday',
                'filter'   => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'tuesday',
            array(
                'header'  => $this->__('Tuesday'),
                'index'   => 'tuesday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'wednesday',
            array(
                'header'  => $this->__('Wednesday'),
                'index'   => 'wednesday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'thursday',
            array(
                'header'  => $this->__('Thursday'),
                'index'   => 'thursday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'friday',
            array(
                'header'  => $this->__('Friday'),
                'index'   => 'friday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'saturday',
            array(
                'header'  => $this->__('Saturday'),
                'index'   => 'saturday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'sunday',
            array(
                'header'  => $this->__('Sunday'),
                'index'   => 'sunday',
                'filter' => false,
                'renderer' => 'aw_storelocator/adminhtml_hour_grid_column_renderer_days',
                'sortable' => false,
                'width'    => '150px'
            )
        );

        $this->addColumn(
            'action',
            array(
                 'header'    => $this->__('Action'),
                 'width'     => '150px',
                 'type'      => 'action',
                 'getter'    => 'getHourId',
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
            if ($key == 'location_ids') $value = implode(',', $value);
            $row[] = $value;
        }
        $adapter->streamWriteCsv($row);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('hour_id');
        $this->getMassactionBlock()->setFormFieldName('hours');
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
