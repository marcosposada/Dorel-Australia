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
 * Adminhtml_Flexibleforms_Renderer_Fields block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Fieldset extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render fields count on Form Grid
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Fields
     */
    public function render(Varien_Object $row)
    {
            $value =  $row->getData($this->getColumn()->getIndex());
            if(!$value)
            {
                return '-----------';
            }
            else
            {
               $fieldsetModel = Mage::getModel('flexibleforms/fieldset')->getCollection()->addFilter('fieldset_id', $value)->getFirstItem();
               return $fieldsetModel->getFieldsetTitle();
            }
    }
}