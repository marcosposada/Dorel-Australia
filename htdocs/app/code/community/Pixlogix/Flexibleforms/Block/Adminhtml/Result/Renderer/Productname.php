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
 * Adminhtml_Result_Renderer_Resultvalue block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <sales@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Productname extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render Result Grid data
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Resultvalue
     */
    public function render(Varien_Object $row, $recordId=0, $fieldId=0, $serializedValue=0)
    {
        //retriving form_id and result id from result grid collection
        $formId = intval($row->getData('form_id'));
        $resultId = intval($row->getData('result_id'));

        //retriving collection from database by result_id
        $resultCollection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('result_id', $resultId)->getFirstItem();
        $productid = $resultCollection->getProductId();
        $_product = Mage::getModel('catalog/product')->loadByAttribute('entity_id', $productid);
        $_pullProduct = Mage::getModel('catalog/product')->loadByAttribute('entity_id', $productid);
        $product_edit_url = Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit', array('id' => $productid));
        $product = '<a target="_blank" rel="external" href="'.$product_edit_url.'">'.$_pullProduct['name'].'</a>';
        return $product;
    }
}