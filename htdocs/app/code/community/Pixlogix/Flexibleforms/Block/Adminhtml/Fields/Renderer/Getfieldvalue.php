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
 * Adminhtml_Fields_Renderer_Getoptions block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Renderer_Getfieldvalue extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render Fields Options into Fields Grid on Form Edit page
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Fields_Renderer_Getoptions
     */
    public function render(Varien_Object $row)
    {
        $fieldId = intval($row->getData('field_id'));
        $logicsId = intval($row->getData('logics_id'));
        $optionsCollection = Mage::getModel('flexibleforms/logics')->getCollection()
                            ->addFieldToFilter('field_id',$fieldId)
                            ->addFieldToFilter('logics_id',$logicsId)
                            ->getFirstItem();

        $fieldValue = unserialize($optionsCollection->getLogicsValue());
        $arrValue = array();
        foreach( $fieldValue  as $key => $value ){
            $arrValue[$key] = '['.$value.']';
        }
        $strvalue = (count($arrValue) > 0) ? implode(', ',$arrValue) : '';
        return $strvalue;
    }
}