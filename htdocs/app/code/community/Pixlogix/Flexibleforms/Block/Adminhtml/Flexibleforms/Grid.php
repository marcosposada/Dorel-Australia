<?php
/**
 * Pixlogix Flexibleforms
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @copyright  Copyright (c) 2015 Pixlogix Flexibleforms (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml_Flexibleforms_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * To initialize Form Grid data
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Grid
     */
    public function __construct()
    {
            parent::__construct();
            $this->setId('flexibleformsGrid');
            // This is the primary key of the database
            $this->setDefaultSort('form_id');
            $this->setDefaultDir('DESC');
            $this->setSaveParametersInSession(true);
            $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
            $prefix = Mage::getConfig()->getTablePrefix();
            $collection = Mage::getModel('flexibleforms/flexibleforms')->getCollection();
            // To join fields table for fields counter
            $collection->getSelect()
                    ->joinLeft(
                            array('r' => $prefix."flexibleforms_result"),
                            'r.form_id = main_table.form_id',
                            array('count(distinct result_id) as result_counter')
                    )
                    ->joinLeft(
                            array('f' => $prefix."flexibleforms_fields"),
                            'f.form_id = main_table.form_id',
                            array('count(distinct field_id) as fields_counter')
                    )
                    ->group(array('main_table.form_id')
            );

            $this->setCollection($collection);
            Mage::dispatchEvent('flexibleforms_block_adminhtml_flexibleforms_grid_prepare_collection', array('grid' => $this));
            return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
            $this->addColumn('form_id', array(
                    'header'    => Mage::helper('flexibleforms')->__('ID'),
                    'align'     => 'right',
                    'width'     => '50px',
                    'index'     => 'form_id',
                    'filter_condition_callback' => array($this, '_formIdFilter'),
            ));

            $this->addColumn('form_title', array(
                    'header'    => Mage::helper('flexibleforms')->__('Title'),
                    'align'     => 'left',
                    'index'     => 'form_title',
            ));

            $this->addColumn('form_url_key', array(
                    'header'    => Mage::helper('flexibleforms')->__('Form Url Key'),
                    'align'     => 'left',
                    'index'     => 'form_url_key',
            ));

            $this->addColumn('fields_counter',array(
                    'header'	=> Mage::helper('flexibleforms')->__('Fields'),
                    'align'	=> 'right',
                    'index'	=> 'fields_counter',
                    'filter'    => false,
                    'width'     => '50px',
                    'renderer'	=> 'Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Fields',
                    'filter_condition_callback' => array($this, '_fieldsCounterFilter')
            ));

            $this->addColumn('result_counter', array(
                    'header'    => Mage::helper('flexibleforms')->__('No. Of Results'),
                    'align'     => 'right',
                    'index'     => 'result_counter',
                    'width'     => '120px',
                    'filter'    => false,
                    'renderer'	=> 'Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Resultfields',
                    'filter_condition_callback' => array($this, '_resultCounterFilter')
            ));
            
            $this->addColumn('form_created_time', array(
                    'header'    => Mage::helper('flexibleforms')->__('Creation Time'),
                    'align'     => 'left',
                    'width'     => '150px',
                    'type'      => 'datetime',
                    'default'   => '--',
                    'index'     => 'form_created_time'
            ));

            $this->addColumn('form_update_time', array(
                    'header'    => Mage::helper('flexibleforms')->__('Update Time'),
                    'align'     => 'left',
                    'width'     => '150px',
                    'type'      => 'datetime',
                    'default'   => '--',
                    'index'     => 'form_update_time'
            ));

            $this->addColumn('form_status', array(
                    'header'    => Mage::helper('flexibleforms')->__('Status'),
                    'align'     => 'left',
                    'width'     => '80px',
                    'index'     => 'form_status',
                    'type'      => 'options',
                    'options'   => array(
                            1 => 'Enabled',
                            2 => 'Disabled'
                    ),
            ));

            $this->addColumn('action', array(
                    'header'    => Mage::helper('flexibleforms')->__('Action'),
                    'width'     => '100',
                    'type'      => 'action',
                    'getter'    => 'getId',
                    'actions'   => array(
                            array(
                                    'caption'   => Mage::helper('flexibleforms')->__('Edit'),
                                    'url'       => array('base'=> '*/*/edit'),
                                    'field'     => 'id'
                            )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));

            $this->addColumn('form_preview', array(
                    'header'    => Mage::helper('flexibleforms')->__('Preview'),
                    'align'     => 'left',
                    'width'     => '80px',
                    'index'     => 'form_preview',
                    'filter'    => false,
                    'sortable'  => false,
                    'renderer'	=> 'Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Formpreview'
            ));
            $this->addExportType('*/*/exportCsv', Mage::helper('flexibleforms')->__('CSV'));
            $this->addExportType('*/*/exportXml', Mage::helper('flexibleforms')->__('XML'));
            $this->addExportType('*/*/exportExcel', Mage::helper('flexibleforms')->__('Excel'));
            return parent::_prepareColumns();
    }

    // Custom filter for Result counter
    public function _resultCounterFilter($collection, $column)
    {
            if (!$value = (int)$column->getFilter()->getValue()) {
                    return $this;
            }
            $collection->getSelect()->having('count(distinct r.result_id)='.$value);
            return $this;
    }

    // Custom filter for Form ID
    public function _formIdFilter($collection, $column)
    {
            if (!$value = (int)$column->getFilter()->getValue()) {
                    return $this;
            }
            $collection->getSelect()->where('main_table.form_id = '.$value);
            return $this;
    }

    // For MassAction into Form Grid
    protected function _prepareMassaction()
    {
            $this->setMassactionIdField('main_table.form_id');
            $this->getMassactionBlock()->setFormFieldName('flexibleforms');

            $this->getMassactionBlock()->addItem('delete', array(
                    'label'    => Mage::helper('flexibleforms')->__('Delete'),
                    'url'      => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('flexibleforms')->__('Are you sure?')
            ));

            $statuses = Mage::getSingleton('flexibleforms/status')->getOptionArray();
            array_unshift($statuses, array('label'=>'', 'value'=>''));
            $this->getMassactionBlock()->addItem('form_status', array(
                    'label'		=> Mage::helper('flexibleforms')->__('Change status'),
                    'url'		=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional'=> array(
                            'visibility'=> array(
                            'name'      => 'form_status',
                            'type'      => 'select',
                            'class'     => 'required-entry',
                            'label'     => Mage::helper('flexibleforms')->__('Status'),
                            'values'    => $statuses
                            )
                    )
            ));
            return $this;
    }

    // For Grid Url
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    // For Row Edit Url
    public function getRowUrl($row)
    {
	return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}