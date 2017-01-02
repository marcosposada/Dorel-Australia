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
 * Country block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Country extends Mage_Core_Block_Template
{
    /**
     * Get Country options into Dropdown
     *
     * @return Pixlogix_Flexibleforms_Block_Country
     */
    public function getCountryField($field_id, $field_title, $required)
    {
        // To get Country collection from magento default
        $_countries = Mage::getResourceModel('directory/country_collection')
                        ->loadData()
                        ->toOptionArray(false);
        //To retrive field value on unsuccess submission
        $helper = Mage::helper('flexibleforms');
        $countrylist = '';
        if (count($_countries) > 0):
            $fieldModel = Mage::getModel('flexibleforms/fields')->getCollection()
                            ->addFieldToFilter('status', array('eq' => 1 ))
                            ->addFieldToFilter('field_id', array('eq' => $field_id ))
                            ->getFirstItem();
            if($required){
                $custom_class = ' validate-select-'.$field_id;
                $custom_validation_msg = $fieldModel->getCustomValidation();
                $required_class = (!empty($custom_validation_msg)) ? $custom_class : ' validate-select';
            }
            else
            {
                $required_class="";
            }

            $countrylist .= '<select name="options['.$field_id.']" id="country" title="'.$field_title.'" class="'.$required_class.'">';
            $countrylist .= '<option value="">'.$this->__('-- Please Select --').'</option>';
            @$value = $helper->getFieldValue($field_id);
                foreach($_countries as $_country):
                    $selectedText='';
                    if((strcmp(trim($_country['value']), trim($value)) == 0))
                    {
                        //If session is define than default select not work
                        if(!isset($_SESSION['options'][$field_id]))
                        {
                            $selectedText = 'selected="selected"';
                        }
                    }
                    //To check session value to fill in form when submition is unsuccess
                    if(isset($_SESSION['options'][$field_id]) &&  $_SESSION['options'][$field_id] == $_country['value'] )
                    {
                           $selectedText = 'selected="selected"';
                    }
                    $countrylist .= '<option value="'.$_country['value'].'" '.$selectedText.'>'.$_country['label'].'</option>';
                endforeach;
            $countrylist .= '</select>';
        endif;
        return $countrylist;
    }
}
?>
