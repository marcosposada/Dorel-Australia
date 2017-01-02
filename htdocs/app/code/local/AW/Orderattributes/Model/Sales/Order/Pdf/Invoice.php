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

class AW_Orderattributes_Model_Sales_Order_Pdf_Invoice
    extends Varien_Object
    implements AW_Lib_Model_AdminhtmlPdf_Sales_Order_Pdf_InvoiceInterface
{
    protected $_order = null;
    protected $_linkedAttributes = null;
    protected $_valueData = null;

    /**
     * Check is can draw section
     *
     * @return bool
     */
    public function canDraw()
    {
        if (!Mage::helper('aw_orderattributes')->isEnabled()) {
            return false;
        }
        return true;
    }

    /**
     * Set order model
     *
     * @param Mage_Sales_Model_Order $order
     * @return mixed
     */
    public function setOrderModel(Mage_Sales_Model_Order $order)
    {
        $this->_order = $order;
    }

    /**
     * Get order model
     *
     * @return Mage_Sales_Model_Order|null
     */
    public function getOrderModel()
    {
        return $this->_order;
    }

    /**
     * Check if needed rendering after address
     *
     * @return bool
     */
    public function hasAfterAddressSection()
    {
        $linedAttributeList = $this->getLinkedAttributes();

        $isBillingAddressHasAttributeList = array_key_exists(
            AW_Orderattributes_Model_Source_Showinblock::BILLING_ADDRESS_BLOCK_CODE,
            $linedAttributeList
        );
        if ($isBillingAddressHasAttributeList) {
            $isBillingAddressHasAttributeList = false;
            $attributes = $linedAttributeList[AW_Orderattributes_Model_Source_Showinblock::BILLING_ADDRESS_BLOCK_CODE];
            foreach ($attributes as $attribute) {
                if ($this->getValueByAttributeId($attribute->getId())) {
                    $isBillingAddressHasAttributeList = true;
                    break;
                }
            }
        }

        $isShippingAddressHasAttributeList = array_key_exists(
            AW_Orderattributes_Model_Source_Showinblock::BILLING_ADDRESS_BLOCK_CODE,
            $linedAttributeList
        );
        if ($isShippingAddressHasAttributeList) {
            $isShippingAddressHasAttributeList = false;
            $attributes = $linedAttributeList[AW_Orderattributes_Model_Source_Showinblock::SHIPPING_ADDRESS_BLOCK_CODE];
            foreach ($attributes as $attribute) {
                if ($this->getValueByAttributeId($attribute->getId())) {
                    $isShippingAddressHasAttributeList = true;
                    break;
                }
            }
        }
        return $isBillingAddressHasAttributeList || $isShippingAddressHasAttributeList;
    }

    /**
     * Get data for render section after addresses
     *
     * @return array
     */
    public function getAddressSectionData()
    {
        $addressSectionData = array(
            'layout_type' => AW_Lib_Model_AdminhtmlPdf_Sales_Order_Pdf_Invoice::TWO_COLUMN_LAYOUT,
            'column'      => array(
                'left'  => array(
                    'title'  => null,
                    'values' => $this->getColumnValuesByBlockCode(
                        AW_Orderattributes_Model_Source_Showinblock::BILLING_ADDRESS_BLOCK_CODE
                    ),
                ),
                'right' => array(
                    'title'  => null,
                    'values' => $this->getColumnValuesByBlockCode(
                        AW_Orderattributes_Model_Source_Showinblock::SHIPPING_ADDRESS_BLOCK_CODE
                    ),
                ),
            ),
        );
        return $addressSectionData;
    }

    /**
     * Check if needed rendering after Payment and shipment
     *
     * @return bool
     */
    public function hasAfterPaymentShipmentSection()
    {
        $linedAttributeList = $this->getLinkedAttributes();

        $isPaymentMethodHasAttributeList = array_key_exists(
            AW_Orderattributes_Model_Source_Showinblock::PAYMENT_METHOD_BLOCK_CODE,
            $linedAttributeList
        );

        if ($isPaymentMethodHasAttributeList) {
            $isPaymentMethodHasAttributeList = false;
            $attributes = $linedAttributeList[AW_Orderattributes_Model_Source_Showinblock::PAYMENT_METHOD_BLOCK_CODE];
            foreach ($attributes as $attribute) {
                if ($this->getValueByAttributeId($attribute->getId())) {
                    $isPaymentMethodHasAttributeList = true;
                    break;
                }
            }
        }

        $isShippingMethodHasAttributeList = array_key_exists(
            AW_Orderattributes_Model_Source_Showinblock::SHIPPING_METHOD_BLOCK_CODE, $linedAttributeList
        );
        if ($isShippingMethodHasAttributeList) {
            $isShippingMethodHasAttributeList = false;
            $attributes = $linedAttributeList[AW_Orderattributes_Model_Source_Showinblock::SHIPPING_METHOD_BLOCK_CODE];
            foreach ($attributes as $attribute) {
                if ($this->getValueByAttributeId($attribute->getId())) {
                    $isShippingMethodHasAttributeList = true;
                    break;
                }
            }
        }
        return $isPaymentMethodHasAttributeList || $isShippingMethodHasAttributeList;
    }

    /**
     * Get data for render section after Payment and shipment
     *
     * @return array
     */
    public function getPaymentShipmentSectionData()
    {
        $paymentSectionData = array(
            'layout_type' => AW_Lib_Model_AdminhtmlPdf_Sales_Order_Pdf_Invoice::TWO_COLUMN_LAYOUT,
            'column'      => array(
                'left'  => array(
                    'title'  => null,
                    'values' => $this->getColumnValuesByBlockCode(
                        AW_Orderattributes_Model_Source_Showinblock::PAYMENT_METHOD_BLOCK_CODE
                    ),
                ),
                'right' => array(
                    'title'  => null,
                    'values' => $this->getColumnValuesByBlockCode(
                        AW_Orderattributes_Model_Source_Showinblock::SHIPPING_METHOD_BLOCK_CODE
                    ),
                ),
            ),
        );
        return $paymentSectionData;
    }

    /**
     * Check if needed rendering custom sections
     *
     * @return bool
     */
    public function hasCustomSection()
    {
        $linedAttributeList = $this->getLinkedAttributes();

        $isOrderReviewHasAttributeList = array_key_exists(
            AW_Orderattributes_Model_Source_Showinblock::ORDER_REVIEW_BLOCK_CODE,
            $linedAttributeList
        );
        if ($isOrderReviewHasAttributeList) {
            $isOrderReviewHasAttributeList = false;
            $attributes = $linedAttributeList[AW_Orderattributes_Model_Source_Showinblock::ORDER_REVIEW_BLOCK_CODE];
            foreach ($attributes as $attribute) {
                if ($this->getValueByAttributeId($attribute->getId())) {
                    $isOrderReviewHasAttributeList = true;
                    break;
                }
            }
        }
        return $isOrderReviewHasAttributeList;
    }

    /**
     * Get data for render custom sections
     *
     * @return array
     */
    public function getCustomSectionData()
    {
        $sectionData = array(
            'layout_type' => AW_Lib_Model_AdminhtmlPdf_Sales_Order_Pdf_Invoice::ONE_COLUMN_LAYOUT,
            'column'      => array(
                'left'  => array(
                    'title' => Mage::helper('aw_orderattributes')->__('Order Review'),
                    'values' => $this->getColumnValuesByBlockCode(
                        AW_Orderattributes_Model_Source_Showinblock::ORDER_REVIEW_BLOCK_CODE
                    ),
                ),
            ),
        );
        return $sectionData;
    }

    /**
     * Get attribute values for PDF
     *
     * @param int $blockCode
     * @return array
     */
    public function getColumnValuesByBlockCode($blockCode)
    {
        $columnValues = array();
        $linkedAttributes = $this->getLinkedAttributes();
        if (array_key_exists($blockCode, $linkedAttributes)) {
            foreach ($linkedAttributes[$blockCode] as $attribute) {
                $pdfRenderer = $attribute->unpackData()
                    ->getTypeModel()
                    ->getBackendPdfRenderer()
                    ->setValue($this->getValueByAttributeId($attribute->getId()))
                ;

                $columnValues[] = array(
                    'value' => $pdfRenderer->getLabel(),
                    'type'  => 'label',
                );
                $value = $pdfRenderer->getValue();
                if (!is_array($value)) {
                    $columnValues[] = array(
                        'value' => $value,
                        'type'  => 'value',
                    );
                } else {
                    foreach ($value as $line) {
                        $columnValues[] = array(
                            'value' => $line,
                            'type'  => 'value',
                        );
                    }
                }
            }
        }
        return $columnValues;
    }

    /**
     * Fetch attributes linked to current order
     * @return array
     */
    public function getLinkedAttributes()
    {
        if (is_null($this->_linkedAttributes)) {
            $this->_linkedAttributes = array();
            foreach ($this->getAttributeCollection() as $attribute) {
                $value = $this->getValueByAttributeId($attribute->getId());
                if (!is_null($value) && !is_null($attribute->getShowInBlock())) {
                    $this->_linkedAttributes[$attribute->getShowInBlock()][] = $attribute;
                }
            }
        }
        return $this->_linkedAttributes;
    }

    /**
     * Get attributes collection by order
     * @return AW_Orderattributes_Model_Resource_Attribute_Collection
     */
    public function getAttributeCollection()
    {
        return Mage::helper('aw_orderattributes/order')->getAttributeCollectionForPdf($this->getOrderModel());
    }

    /**
     * Get attribute value by attribute id
     *
     * @param int $attributeId
     * @return string|null
     */
    public function getValueByAttributeId($attributeId)
    {
        if (is_null($this->_valueData)) {
            $this->_valueData = array();
            $collection = Mage::helper('aw_orderattributes/order')->getAttributeValueCollectionForQuote(
                $this->getOrderModel()->getQuoteId()
            );
            foreach ($collection as $item) {
                $this->_valueData[$item->getData('attribute_id')] = $item->getData('value');
            }
        }
        return array_key_exists($attributeId, $this->_valueData) ? $this->_valueData[$attributeId] : null;
    }
}