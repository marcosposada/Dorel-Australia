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
 * Adminhtml_Flexibleforms_Edit_Tab_Fields block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Fields extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareLayout(){
        parent::_prepareLayout();
    }

    /**
     * To initialize Fields Grid on Form Edit Tab
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Edit_Tab_Fields
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('fieldsGrid');
        // This is the primary key of the database
        $this->setDefaultSort('field_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    // To "Add New Field" button above fields grid
    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();//get the parent class buttons
        $addButton = $this->getLayout()->createBlock('adminhtml/widget_button') //create the add button
                ->setData(array(
                        'label'     => Mage::helper('adminhtml')->__('Add New Field'),
                        'onclick'   => "setLocation('".$this->getAddFieldUrl()."')",
                        'class'     => 'add'
                ))->toHtml();
        return $addButton.$html;
    }

    // Add new field url
    public function getAddFieldUrl()
    {
        return $this->getUrl('*/flexibleforms_fields/edit', array ('form_id' =>$this->getRequest()->getParam('id')));
    }

    // To work with Grid Serializer data for Mass Action
    protected function getAdditionalJavascript()
    {
        return 'window.fieldsGrid_massactionJsObject = fieldsGrid_massactionJsObject;';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('flexibleforms/fields')
                        ->getCollection()
                        ->addFilter('form_id', $this->getRequest()->getParam('id'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('field_id', array(
            'header'	=> Mage::helper('flexibleforms')->__('Field ID'),
            'width'	=> '25px',
            'align'	=> 'left',
            'index'	=> 'field_id',
        ));

        $this->addColumn('title', array(
            'header'	=> Mage::helper('flexibleforms')->__('Title'),
            'width'	=> '200px',
            'align'	=> 'left',
            'index'	=> 'title',
        ));

        $formId = $this->getRequest()->getParam('id');
        $this->addColumn('fieldset_id', array(
            'header'	=> Mage::helper('flexibleforms')->__('Fieldset'),
            'width'	=> '20px',
            'align'	=> 'left',
            'index'	=> 'fieldset_id',
            'type'      => 'options',
            'options'   => Mage::getModel('flexibleforms/fieldset')->getFieldsetOption($formId),
            'renderer'	=> 'flexibleforms/adminhtml_flexibleforms_renderer_fieldset'
        ));

        $fieldsModel = Mage::getModel('flexibleforms/fields');
        $this->addColumn('type', array(
            'header'	=> Mage::helper('flexibleforms')->__('Field Type'),
            'width'	=> '50px',
            'align'	=> 'left',
            'index'	=> 'type',
            'type'      => 'options',
            'options'   => $fieldsModel->getFieldTypesOptions(),
            
        ));

        $this->addColumn('options', array(
            'header'	=> Mage::helper('flexibleforms')->__('Options'),
            'width'	=> '20px',
            'align'	=> 'left',
            'index'	=> 'options',
        ));

        $this->addColumn('options_value', array(
            'header'	=> Mage::helper('flexibleforms')->__('Options Value'),
            'width'	=> '200px',
            'align'	=> 'left',
            'index'	=> 'options_value',
            'renderer'	=> 'flexibleforms/adminhtml_fields_renderer_getoptions'
        ));

        $this->addColumn('position', array(
            'header'	=> Mage::helper('flexibleforms')->__('Position'),
            'width'	=> '25px',
            'align'	=> 'left',
            'index'	=> 'position',
        ));

        $this->addColumn('required', array(
            'header'	=> Mage::helper('flexibleforms')->__('Required'),
            'width'     => '25px',
            'align'	=> 'left',
            'index'	=> 'required',
            'type'	=> 'options',
            'options'	=> array(
                1 => 'Yes',
                0 => 'No',
            ),
        ));

        $this->addColumn('status', array(
            'header'	=> Mage::helper('flexibleforms')->__('Status'),
            'width'	=> '25px',
            'align'	=> 'left',
            'index'	=> 'status',
            'type'      => 'options',
            'options'	=> array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        return parent::_prepareColumns();
    }

    // For MassAction into Fields Grid Serializer
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('field_id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleforms')->__('Delete'),
            'url'      => $this->getUrl('*/flexibleforms_fields/massDelete', array('form_id' => $this->getParam('id'))),
            'confirm'  => Mage::helper('flexibleforms')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('flexibleforms/status')->getOptionArray();

        $this->getMassactionBlock()->addItem('status', array(
            'label'		=> Mage::helper('flexibleforms')->__('Change status'),
            'url'		=> $this->getUrl('*/flexibleforms_fields/massStatus', array('form_id' => $this->getParam('id'))),
            'additional'=> array(
                'visibility' => array(
                    'name'	=> 'status',
                    'type'	=> 'select',
                    'class'	=> 'required-entry',
                    'label'	=> Mage::helper('flexibleforms')->__('Status'),
                    'values'    => $statuses
                )
            )
        ));
        return $this;
    }

    // this method is reuired if you want ajax grid
    public function getGridUrl()
    {
        return $this->getUrl('*/*/fieldslist', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/flexibleforms_fields/edit', array('id' => $row->getId(), 'form_id' => $row->getFormId()));
    }

    public function canShowTab()
    {
	return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getTabLabel()
    {
        return $this->__('Fields List');
    }

    public function getTabTitle()
    {
        return $this->__('Fields List');
    }

    public function getAfter()
    {
        return 'email_settings';
    }
}