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
 * Checkbox block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleforms_Block_Checkbox extends Mage_Core_Block_Template
{
    public function getCheckboxField($_fieldId)
    {
        $fieldOptions = Mage::getModel('flexibleforms/fields')->getCollection();
        $fieldOptions->addFieldToFilter('field_id', array('eq'=>$_fieldId));
        $fieldOptions->setOrder('position','asc');
        //To retrive field value on unsuccess submission
        $helper = Mage::helper('flexibleforms');
        
        $fieldArray = array();
        foreach($fieldOptions as $fieldOption)
        {
            $fieldArray['serializeOptions'] = $fieldOption->getOptionsValue();
            $fieldArray['required']         = $fieldOption->getRequired();
            $fieldArray['field_id']         = $fieldOption->getFieldId();
            $fieldArray['field_title']      = $fieldOption->getFieldTitle();
            $fieldArray['custom_validation']= $fieldOption->getCustomValidation();
        }

        $field_id   = $fieldArray['field_id'];
        $field_title= $fieldArray['field_title'];

        $serializeOptions = '';
        $arrayOptions = unserialize($fieldArray['serializeOptions']);
        $arrayCount   = count($arrayOptions);
        if($arrayCount > 0)
        {
            $arrayOptions=Mage::getModel('flexibleforms/flexibleforms')->getOptionControl($arrayOptions);

            $lastOptionCount = 0;
            $serializeOptions .= '<ul class="options-list">';

            //Store option value using trim function to remove white space
            foreach($arrayOptions as $key => $arrayOption)
            {
                if($arrayCount === ++$lastOptionCount){
                    if($fieldArray['required']){
                        $custom_message = $fieldArray['custom_validation'];
                        $custom_class = ' validate-one-required-by-name-'.$fieldArray['field_id'];
                        $required_class = (!empty($custom_message)) ? $custom_class : ' validate-one-required-by-name';
                    }
                }else{
                    $required_class = '';
                }

                $selectedText='';
                if(strpos($key,'{{selected}}'))
                {
                    $key=trim(str_replace('{{selected}}','',$key));
                    if(!isset($_SESSION['options'][$_fieldId]))
                    {
                        $selectedText = 'checked="checked"';
                    }
                }
                if(strpos($arrayOption,'{{selected}}'))
                {
                    $arrayOption=trim(str_replace('{{selected}}','',$arrayOption));
                    if(!isset($_SESSION['options'][$_fieldId]))
                    {
                        $selectedText = 'checked="checked"';
                    }
                }
                if($key == '0')
                {
                    $key = '';
                }
                
                if(isset($_SESSION['options'][$_fieldId]) && in_array(trim($key),$_SESSION['options'][$_fieldId]) ){
                   $selectedText = 'checked="checked"';
                }
                $serializeOptions .= '<li><input type="checkbox" name="options['.$field_id.'][]" id="field_'.$field_id.'_'.$key.'" class="checkbox'.$required_class.'" '.$selectedText.' title="'.$arrayOption.'" value="'.$key.'" /><label for="field_'.$field_id.'_'.$key.'"> '.$arrayOption.'</label></li>';
            }
            $serializeOptions .= '</ul>';
        }
        unset($_SESSION['options'][$_fieldId]);
        return $serializeOptions;
    }
}