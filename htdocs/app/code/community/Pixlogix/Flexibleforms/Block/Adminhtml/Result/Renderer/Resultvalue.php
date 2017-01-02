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
class Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Resultvalue extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render Result Grid data
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Result_Renderer_Resultvalue
     */
    public function render(Varien_Object $row, $recordId=0, $fieldId=0, $serializedValue=0)
    {
	//Retiving field_id from grid column index value
	$fieldId = ($fieldId) ? $fieldId : intval($this->getColumn()->getIndex());

	//retriving form_id and result id from result grid collection
	$formId = intval($row->getData('form_id'));
	$resultId = intval($row->getData('result_id'));

	//retriving collection from database by field_id and result_id
	$fieldsCollection = Mage::getModel('flexibleforms/fields')->getCollection()->addFieldToFilter('field_id', $fieldId)->getFirstItem();
	$resultCollection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('result_id', $resultId)->getFirstItem();

	$unserializeResult = unserialize($resultCollection['value']);
	$arrComboFields = array('multiselect','checkbox');

	$options = array();
	if(in_array($fieldsCollection['type'], $arrComboFields))
	{
            foreach($unserializeResult[$fieldId] as $fielsOptions)
            {
		$options[] = '['.trim($fielsOptions).']';
            }
            $optionStr = implode(',',$options);
            $unserializeResult[$fieldId] = $optionStr;
	}

	if($fieldsCollection['type'] == 'sendcopytome')
	{
             if($unserializeResult[$fieldId]==1)
            {
                $unserializeResult[$fieldId] = $this->__('Yes');
            }
            else if($unserializeResult[$fieldId]==2)
            {
                $unserializeResult[$fieldId] = $this->__('No');
            }
        }
        if($fieldsCollection['type'] == 'terms')
	{
             if($unserializeResult[$fieldId]=='Yes')
            {
                $unserializeResult[$fieldId] = $this->__('Yes');
            }
            else if($unserializeResult[$fieldId]=='No')
            {
                $unserializeResult[$fieldId] = $this->__('No');
            }
        }

	if($fieldsCollection['type'] == 'image')
	{
            $getMediaUrl = Mage::getBaseUrl("media") .'flexibleforms/images/';
            $html = '<img ';
            $html .= 'id="' . $this->getColumn()->getId() . '" ';
            $html .= 'src="'. $getMediaUrl . $unserializeResult[$fieldId] . '"';
            $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss().'"';
            $html .= 'style="weight:75px;height:75px"' . '"/>';
            if($unserializeResult[$fieldId])
            	return $html;

            return '';
        }

        if($fieldsCollection['type'] == 'file')
        {
            $val = $unserializeResult[$fieldId];
            $fileHtml = "<a href='".$this->getUrl('*/*/download',array('_current'=>true,'field_id'=>$fieldId,'result_id'=>$resultId))."' target='_blank'>".$val ."</a>";
            if($unserializeResult[$fieldId])
                    return $fileHtml;
            return '';
        }
        return $unserializeResult[$fieldId];
    }
}