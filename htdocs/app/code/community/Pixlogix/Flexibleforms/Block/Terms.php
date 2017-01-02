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
 * Terms block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleforms_Block_Terms extends Mage_Core_Block_Template
{
    public function getTermsField($_fieldId, $_fieldTitle)
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
            $fieldArray['field_title']         = $fieldOption->getFieldTitle();
            $fieldArray['custom_validation']= $fieldOption->getCustomValidation();
        }

        $field_id = $fieldArray['field_id'];
        $serializeOptions = '';
        $arrayOptions = unserialize($fieldArray['serializeOptions']);
        $arrayCount = count($arrayOptions);
        if($arrayCount > 0)
        {
            $arrayOptions=Mage::getModel('flexibleforms/flexibleforms')->getOptionControl($arrayOptions);
            if(empty($arrayOptions[0]))
            {
                unset($arrayOptions[0]);
            }
            $lastOptionCount = 0;
            $serializeOptions .= '<ul class="options-list">';
            foreach($arrayOptions as $key => $arrayOption)
            {
                if($arrayCount === ++$lastOptionCount){
                    if($fieldArray['required']){
                        $custom_message = $fieldArray['custom_validation'];
                        $custom_class = ' validate-one-required-by-name-'.$fieldArray['field_id'];
                        $required_class = (!empty($custom_message)) ? $custom_class : ' validate-one-required-by-name';
                    }
                    else
                    {
                        $required_class='';
                    }
                }else{
                    $required_class = '';
                }
                $selectedText = '';
                if(strpos($arrayOption,'{{selected}}'))
                {
                    $arrayOption=Mage::helper('cms')->getPageTemplateProcessor()->filter(trim(str_replace('{{selected}}','',$arrayOption)));
                    if(!isset($_SESSION['options'][$_fieldId]))
                    {
                        $selectedText = 'checked="checked"';
                    }
                }

                if(isset($_SESSION['options'][$_fieldId]) && @in_array('Yes',$_SESSION['options'][$_fieldId]) ){
                   $selectedText = 'checked="checked"';
                }
                $serializeOptions .= '<li><input type="checkbox" name="options['.$field_id.'][]" id="options_'.$field_id.'_terms" class="checkbox'.$required_class.'" '.$selectedText.' title="'.$_fieldTitle.'" value="'.$this->__('Yes').'" /><label for="options_'.$field_id.'_terms"> '.$arrayOption.'</label></li>';
            }
            $serializeOptions .= '</ul>';
        }
        unset($_SESSION['options'][$_fieldId]);
        return $serializeOptions;
    }
}