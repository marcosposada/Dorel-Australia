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
 * @package    AW_Orderattributes
 * @version    1.0.4
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Orderattributes_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    public function setCollection($collection)
    {
        $collection = Mage::getResourceModel('aw_orderattributes/sales_order_collection')
            ->addAttributesToOrderCollection()
        ;
        return parent::setCollection($collection);
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        foreach ($this->getOrderAttributeCollection() as $attribute) {
            $gridRenderer = $attribute->unpackData()->getTypeModel()->getBackendGridRenderer();
            $this->addColumnAfter($gridRenderer->getColumnId(), $gridRenderer->getColumnProperties(), 'grand_total');
        }

        $this->addColumn(
            'action',
            array(
                'header'    => $this->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => $this->__('Edit'),
                        'url'       => array('base' => 'adminhtml/sales_order/view'),
                        'field'     => 'order_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            )
        );

        $this->sortColumnsByOrder();
        return $this;
    }

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        foreach ($this->getMassactionBlock()->getItems() as $item) {
            $url = $item->getData('url');
            if (strpos($url, 'aw_orderattributes_admin/sales_order') === FALSE) {
                continue;
            }
            $newUrl = str_replace(
                'aw_orderattributes_admin/sales_order',
                'adminhtml/aworderattributes_order',
                $url
            );
            $item->setData('url', $newUrl);
        }
        return $this;
    }

    protected function getOrderAttributeCollection()
    {
        return Mage::helper('aw_orderattributes/order')->getAttributeCollectionForOrderGrid();
    }

    public function removeColumn($columnId)
    {
        if (isset($this->_columns[$columnId])) {
            unset($this->_columns[$columnId]);
        }
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));
    }
}