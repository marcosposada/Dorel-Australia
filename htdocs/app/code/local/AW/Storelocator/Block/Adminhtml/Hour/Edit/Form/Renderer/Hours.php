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
 * @package    AW_Storelocator
 * @version    1.1.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Storelocator_Block_Adminhtml_Hour_Edit_Form_Renderer_Hours extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('time');
    }

    public function getName()
    {
        $name = parent::getName();
        if (strpos($name, '[]') === false) {
            $name.= '[]';
        }
        return $name;
    }

    public function getElementHtml()
    {
        $this->addClass('select');

        $value_hrs = 0;
        $value_min = 0;

        if( $value = $this->getValue() ) {
            $values = explode(':', $value);
            if( is_array($values) && count($values) == 3 ) {
                $value_hrs = $values[0];
                $value_min = $values[1];
            }
        }

        $html = '<input type="hidden" id="' . $this->getHtmlId() . '" />';
        $html .= '<select name="'. $this->getName() . '" '.$this->serialize($this->getHtmlAttributes()).' style="width:40px">'."\n";
        for( $i=0;$i<24;$i++ ) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $html.= '<option value="'.$hour.'" '. ( ($value_hrs == $i) ? 'selected="selected"' : '' ) .'>' . $hour . '</option>';
        }
        $html.= '</select>'."\n";

        $html.= '&nbsp;:&nbsp;<select name="'. $this->getName() . '" '.$this->serialize($this->getHtmlAttributes()).' style="width:40px">'."\n";
        for( $i=0;$i<60;$i++ ) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $html.= '<option value="'.$hour.'" '. ( ($value_min == $i) ? 'selected="selected"' : '' ) .'>' . $hour . '</option>';
        }
        $html.= '</select>'."\n";

        $html.= $this->getAfterElementHtml();
        return $html;
    }
}