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
 * Multiselect block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Multiselect extends Mage_Core_Block_Template
{
    /**
     * Get Selectbox options into Multi Select Dropdown
     *
     * @return Pixlogix_Flexibleforms_Block_Multiselect
     */
    public function getMultiselectField($_fieldId)
    {
        $fieldOptions = Mage::getModel('flexibleforms/fields')->getCollection();
        $fieldOptions->addFieldToFilter('field_id', array('eq'=>$_fieldId));
        $fieldOptions->setOrder('position','asc');

        //To retrive field value on unsuccess submission
        $field_value = Mage::helper('flexibleforms');

        $fieldArray = array();

        foreach($fieldOptions as $fieldOption)
        {
            $fieldArray['serializeOptions'] = $fieldOption->getOptionsValue();
            $fieldArray['required']         = $fieldOption->getRequired();
            $fieldArray['field_id']         = $fieldOption->getFieldId();
            $fieldArray['field_title']      = $fieldOption->getFieldTitle();
            $fieldArray['custom_validation']= $fieldOption->getCustomValidation();
        }

        $field_id    = $fieldArray['field_id'];
        $field_title = $fieldArray['field_title'];

        $serializeOptions = '';
        $arrayOptions = unserialize($fieldArray['serializeOptions']);
        $arrayCount   = count($arrayOptions);
        if($arrayCount > 0)
        {
            $arrayOptions = Mage::getModel('flexibleforms/flexibleforms')->getOptionControl($arrayOptions);
            if(empty($arrayOptions[0]))
            {
                unset($arrayOptions[0]);
            }

            if($fieldArray['required']){
                $custom_class = ' validate-select-'.$_fieldId;
                $required_class = (!empty($fieldArray['custom_validation'])) ? $custom_class : ' validate-select';
            }
            else
            {
                $required_class="";
            }
            $serializeOptions .= '<select name="options['.$field_id.'][]" id="options_'.$field_id.'" class="'.$required_class.'" multiple="multiple">';

            foreach($arrayOptions as $key=>$arrayOption)
            {
                $selectedText = '';
                if(strpos($key,'{{selected}}'))
                {
                    $key=trim(str_replace('{{selected}}','',$key));
                    if(!isset($_SESSION['options'][$_fieldId]))
                    {
                        $selectedText = 'selected="selected"';
                    }
                }
                if(strpos($arrayOption,'{{selected}}'))
                {
                    $arrayOption=trim(str_replace('{{selected}}','',$arrayOption));
                    if(!isset($_SESSION['options'][$_fieldId]))
                    {
                        $selectedText = 'selected="selected"';
                    }
                }

                if($key == '0')
                {
                    $key = '';
                }

                if(isset($_SESSION['options'][$_fieldId]) && in_array($key,$_SESSION['options'][$_fieldId]) ){
                    $selectedText = 'selected="selected"';
                }
                $serializeOptions .= '<option value="'.$key.'" '.$selectedText.' >'.$this->__(trim($arrayOption)).'</option>';
            }
            $serializeOptions .= '</select>';
        }
        unset($_SESSION['options'][$_fieldId]);
        return $serializeOptions;
    }
}