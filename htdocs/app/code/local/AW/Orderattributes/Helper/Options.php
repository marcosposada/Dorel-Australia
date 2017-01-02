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


class AW_Orderattributes_Helper_Options extends Mage_Core_Helper_Data
{
    /**
     * for frontend form & backend grid
     *
     * @param AW_Orderattributes_Model_Attribute $attribute
     * @param null $storeId
     * @param bool $isAddPleaseSelect
     * @return array
     */
    public function getOptionsForAttributeByStoreId(
        AW_Orderattributes_Model_Attribute $attribute, $storeId = null, $isAddPleaseSelect = true
    ) {
        if (is_null($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }
        $options = $attribute->getStoreOptions();
        uasort($options, array($this, '_sortBySortOrder'));
        $resultOptions = array();
        if ($isAddPleaseSelect) {
            $resultOptions = array(
                '' => $this->__('---Please Select---')
            );
        }
        foreach($options as $optionId => $item) {
            if (isset($item['label'][$storeId]) && strlen(trim($item['label'][$storeId])) > 0){
                $resultOptions[$optionId] = $item['label'][$storeId];
            } else {
                $resultOptions[$optionId] = $item['label'][0];
            }
        }
        return $resultOptions;
    }

    /**
     * for backend form edit multiselect
     *
     * @param AW_Orderattributes_Model_Attribute $attribute
     * @param null $storeId
     * @param bool $isAddPleaseSelect
     * @return array
     */
    public function getOptionsForAttributeByStoreIdAsArray(
        AW_Orderattributes_Model_Attribute $attribute,
        $storeId = null,
        $isAddPleaseSelect = true
    ) {
        if (is_null($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }
        $options = $attribute->getStoreOptions();
        uasort($options, array($this, '_sortBySortOrder'));
        $resultOptions = array();
        if ($isAddPleaseSelect) {
            $resultOptions = array(
                '' => $this->__('---Please Select---')
            );
        }
        foreach($options as $optionId => $item) {
            if (isset($item['label'][$storeId]) && strlen(trim($item['label'][$storeId])) > 0){
                $resultOptions[] = array(
                    'value' => $optionId,
                    'label' => $item['label'][$storeId],
                );
            } else {
                $resultOptions[] = array(
                    'value' => $optionId,
                    'label' => $item['label'][0],
                );
            }
        }
        return $resultOptions;
    }

    public function getOptionsForYesnoAttribute($isAddPleaseSelect = true, $isChangeZeroToEmpty = false) {
        $optionSource = array();
        $source = Mage::getModel('aw_orderattributes/attribute_type_yesno')->getAvailableOptionArray();
        foreach($source as $item) {
            if ($item['value'] === 0 || $item['value'] === '0') {
                if (!$isAddPleaseSelect) {
                    continue;
                }
                if ($isChangeZeroToEmpty) {
                    $item['value'] = '';
                }
            }
            $optionSource[$item['value']] = $item['label'];
        }
        return $optionSource;
    }

    private function _sortBySortOrder($a, $b)
    {
        if ($a['sort_order'] < $b['sort_order']) {
            return -1;
        } else if($a['sort_order'] > $b['sort_order']) {
            return 1;
        }
        return 0;
    }
}