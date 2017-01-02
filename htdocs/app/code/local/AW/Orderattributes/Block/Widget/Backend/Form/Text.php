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


class AW_Orderattributes_Block_Widget_Backend_Form_Text
    extends AW_Orderattributes_Block_Widget_Backend_FormAbstract
{
    public function getFieldId()
    {
        return $this->_getCode();
    }

    public function getFieldType()
    {
        return 'text';
    }

    public function getFieldTypeRenderer()
    {
        return null;
    }

    public function getFieldProperties()
    {
        $properties = array(
            'label'    => $this->_getLabel(),
            'name'     => $this->_getCode(),
            'class'    => $this->_getValidateCssClassNames(),
            'required' => $this->getProperty('validation_rules/is_required') ? true : false,
            'value'    => $this->_getValue(),
        );
        return $properties;
    }

    protected function _getValidateCssClassNames()
    {
        $result = "";
        if ($this->getProperty('validation_rules/minimum_text_length') ||
            $this->getProperty('validation_rules/maximum_text_length')
        ) {
            $result .= "validate-length ";
            if ($length = $this->getProperty('validation_rules/minimum_text_length')) {
                $result .= "minimum-length-" . (int)$length . " ";
            }
            if ($length = $this->getProperty('validation_rules/maximum_text_length')) {
                $result .= "maximum-length-" . (int)$length . " ";
            }
        }
        switch($this->getProperty('validation_rules/input_validation')) {
            case AW_Orderattributes_Model_Source_Validation::ALPHANUMERIC_CODE:
                $result .= "validate-alphanum-with-spaces ";
                break;
            case AW_Orderattributes_Model_Source_Validation::NUMERIC_CODE:
                $result .= "validate-number ";
                break;
            case AW_Orderattributes_Model_Source_Validation::ALPHA_CODE:
                $result .= "validate-alpha ";
                break;
            case AW_Orderattributes_Model_Source_Validation::URL_CODE:
                $result .= "validate-url ";
                break;
            case AW_Orderattributes_Model_Source_Validation::EMAIL_CODE:
                $result .= "validate-email ";
                break;
            default:
        }
        return trim($result);
    }
}