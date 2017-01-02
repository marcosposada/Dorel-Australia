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
 * Adminhtml_Result_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Result_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Result Grid block
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Result_Grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('resultGrid');
        // This is the primary key of the database
        $this->setDefaultSort('result_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function filterProductsku($collection, $column)
    {   
        $formId=$this->getRequest()->getParam('form_id');
        if (!$value = $column->getFilter()->getValue())
        {
            return $this;
            exit;
        }
        $resultIds = Mage::getModel('flexibleforms/result')->filterProductSkuColumn($value);
        $this->getCollection()->addFieldToFilter('result_id', array('in' => $resultIds));
    }

    protected function filterProductName($collection, $column)
    {
	$formId=$this->getRequest()->getParam('form_id');
	if (!$value = $column->getFilter()->getValue())
        {
            return $this;
            exit;
        }
	$resultIds = Mage::getModel('flexibleforms/result')->filterProductNameColumn($value);
	$this->getCollection()->addFieldToFilter('result_id', array('in' => $resultIds));
    }

    protected function filterField($collection, $column)
    {
        $field_id = $column->getIndex();
        if (!$value = $column->getFilter()->getValue())
        {
            return $this;
            exit;
        }
	$formId=$this->getRequest()->getParam('form_id');
        //Function to filter result grid
        $resultIds =  Mage::getModel('flexibleforms/result')->resultFilter($formId,$field_id,$value);
        $this->getCollection()->addFieldToFilter('result_id', array('in' => $resultIds));
    }

    protected function _prepareCollection()
    {
        $formId=$this->getRequest()->getParam('form_id');
        $collection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('form_id', array('eq' => $formId));
        $this->setCollection($collection);
        Mage::dispatchEvent('flexibleforms_block_adminhtml_results_grid_prepare_collection', array('grid' => $this));
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $formFields =  Mage::getModel('flexibleforms/fields')->getCollection()
                        ->addFieldToFilter('form_id', $formId)
                        ->addFieldToFilter('status',1)
                        ->setOrder('position','ASC');

        $this->addColumn('result_id', array(
            'header'    => Mage::helper('flexibleforms')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'result_id'
        ));

        //To add grid column for product sku and product name
        if(Mage::helper('flexibleforms')->enabledProductInquiry() && Mage::helper('flexibleforms')->isProductInquiryForm($formId)):

                $this->addColumn('product_sku', array(
                        'header'    => Mage::helper('flexibleforms')->__('Product Sku'),
                        'align'     =>'left',
                        'width'     => '50px',
                        'sortable'  => false,
                        'filter_condition_callback' => array($this, 'filterProductsku'),
                        'renderer'  => 'Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Productsku'
                ));

                $this->addColumn('product_name', array(
                        'header'    => Mage::helper('flexibleforms')->__('Product Name'),
                        'align'     =>'left',
                        'width'     => '50px',
                        'index'     => 'product_name',
                        'sortable'  => false,
                        'filter_condition_callback' => array($this, 'filterProductName'),
                        'renderer'  => 'Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Productname'
                ));
        endif;

        // To unique name of column
        $i=1;
        foreach($formFields as $key=>$fields)
        {
             $column =  array(
                     'header'    => Mage::helper('flexibleforms')->__($fields->getTitle()),
                     'align'     =>'left',
                     'sortable'  => false,
                     'name'      => $i++,
                     'width'     => '50px',
                     'index'     => $fields->getFieldId(),
                     'filter_condition_callback' => array($this, 'filterField'),
                     'renderer'  => 'Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Resultvalue'
             );

             $arrType =array('select','checkbox','radio','multiselect','sendcopytome');
             if(in_array($fields->getType(),$arrType))
             {
                 $arrControlOption =Mage::getModel('flexibleforms/result')->getOptionArray($fields->getFieldId());
                 $column['type']='options';
                 $column['options']= $arrControlOption;
             }
             else if($fields->getType()=='country')
             {
                $arrCountyOption =Mage::getModel('flexibleforms/result')->getCountryArray($fields->getFieldId()); 
                $column['type']='options';
                $column['options']= $arrCountyOption;
             }
             else if($fields->getType()=='date' || $fields->getType()=='datetime')
             {
                $column['type']='date';
             }
             $this->addColumn('field_'.$fields->getFieldId(),$column);
        }

        $this->addColumn('sender_ip', array(
            'header'    => Mage::helper('flexibleforms')->__('IP'),
            'sortable'  => true,
            'width'     => '50px',
            'index'     => 'sender_ip'
        ));

        $this->addColumn('submit_time', array(
            'header'    => Mage::helper('flexibleforms')->__('Submit Time'),
            'sortable'  => true,
            //'filter'    => false,
            'type'      => 'datetime',
            'width'     => '50px',
            'index'     => 'submit_time'
        ));

        $this->addColumn('browser_info', array(
            'header'    => Mage::helper('flexibleforms')->__('Browser Info'),
            'sortable'  => true,
            //'filter'    => false,
            'width'     => '50px',
            'index'     => 'browser_info'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('flexibleforms')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('flexibleforms')->__('Excel'));

        return parent::_prepareColumns();
    }

    // For Mass Delete Action into Result Grid
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('result_id');
        $this->getMassactionBlock()->setFormFieldName('flexibleforms_result');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleforms')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('flexibleforms')->__('Are you sure?')
        ));
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return false;
    }
}