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

class AW_Orderattributes_Model_Resource_Sales_Order_Collection
    extends Mage_Sales_Model_Mysql4_Order_Grid_Collection
{
    protected function _construct()
    {
        parent::_construct();
        $this
            ->addFilterToMap('increment_id', 'main_table.increment_id')
            ->addFilterToMap('store_id', 'main_table.store_id')
            ->addFilterToMap('created_at', 'main_table.created_at')
            ->addFilterToMap('billing_name', 'main_table.billing_name')
            ->addFilterToMap('shipping_name', 'main_table.shipping_name')
            ->addFilterToMap('base_grand_total', 'main_table.base_grand_total')
            ->addFilterToMap('grand_total', 'main_table.grand_total')
            ->addFilterToMap('status', 'main_table.status')
        ;
    }

    public function addFieldToFilter($field, $condition = null)
    {
        $attribute = Mage::getModel('aw_orderattributes/attribute')->loadByCode($field);
        if ($attribute->getId()) {
            $valueType = $attribute->getTypeModel()->getValueType();
            $defaultValue = str_replace(array("\r", "\n"), ' ', $attribute->getDefaultValue());
            $fieldValue = "IFNULL({$attribute->getCode()}_{$valueType}.value, '{$defaultValue}')";
            if (isset($condition['like'])) {
                $this->getSelect()->where($fieldValue . ' LIKE ?', $condition['like']);
            } elseif (isset($condition['from']) || isset($condition['to'])) {
                $fromToSql = '';
                if (isset($condition['from'])) {
                    $from = $this->getConnection()->convertDate($condition['from']);
                    $fromToSql .= $this->getConnection()->quoteInto("$fieldValue >= ?", $from);
                }
                if (isset($condition['to'])) {
                    $fromToSql .= empty($fromToSql) ? '' : ' AND ';
                    $to = $this->getConnection()->convertDate($condition['to']);
                    $fromToSql .= $this->getConnection()->quoteInto("$fieldValue <= ?", $to);
                }
                $this->getSelect()->where($fromToSql);
            } elseif (isset($condition['eq'])) {
                if ($attribute->getType() == 'multipleselect') {
                    $this->getSelect()->where('FIND_IN_SET(?, ' . $fieldValue . ')', (int)$condition['eq']);
                } else {
                    $this->getSelect()->where($fieldValue . ' = ?', $condition['eq']);
                }
            }
            return $this;
        }
        parent::addFieldToFilter($field, $condition);
        return $this;
    }

    /**
     * @return AW_Orderattributes_Model_Resource_Sales_Order_Collection
     */
    public function addAttributesToOrderCollection()
    {
        $attributesCollection = Mage::helper('aw_orderattributes/order')->getAttributeCollectionForOrderGrid();
        $this->getSelect()->joinLeft(
            array("sales_flat_order" => $this->getTable('sales/order')),
            "sales_flat_order.entity_id = main_table.entity_id",
            array()
        );
        foreach ($attributesCollection as $attribute) {
            $valueType = $attribute->getTypeModel()->getValueType();
            $this->getSelect()->joinLeft(
                array("{$attribute->getCode()}_{$valueType}" => "aw_orderattributes_value_{$valueType}"),
                "{$attribute->getCode()}_{$valueType}.quote_id = sales_flat_order.quote_id AND {$attribute->getCode()}_{$valueType}.attribute_id = {$attribute->getId()}",
                array()
            );
            $defaultValue = preg_split('/\r\n|\r|\n/', $attribute->getDefaultValue());
            $fieldValue = "IFNULL({$attribute->getCode()}_{$valueType}.value, '{$defaultValue[0]}')";
            $this->getSelect()->columns(array($attribute->getCode() => $fieldValue));
        }
        return $this;
    }

    /**
     * @param string $attribute
     * @param string $dir
     * @return AW_Orderattributes_Model_Resource_Sales_Order_Collection|Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function addAttributeToSort($attribute, $dir='asc')
    {
        $attributeModel = Mage::getModel('aw_orderattributes/attribute')->loadByCode($attribute);
        if (is_null($attributeModel->getId())) {
            return parent::addAttributeToSort($attribute, $dir);
        }
        $valueType = $attributeModel->getTypeModel()->getValueType();
        $defaultValue = str_replace(array("\r", "\n"), ' ', $attributeModel->getDefaultValue());
        $fieldValue = "IFNULL({$attributeModel->getCode()}_{$valueType}.value, '{$defaultValue}')";
        $this->getSelect()->order("{$fieldValue} {$dir}");
        return $this;
    }

}