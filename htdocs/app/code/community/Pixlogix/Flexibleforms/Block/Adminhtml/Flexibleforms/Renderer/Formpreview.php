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
 * Adminhtml_Flexibleforms_Renderer_Formpreview block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Formpreview extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     *  To render Form Preview link on Form Grid
     *
     * @return Pixlogix_Flexibleforms_Block_Adminhtml_Flexibleforms_Renderer_Formpreview
     */
    public function render(Varien_Object $row)
    {
            $storeId = Mage::app()->getStore()->getStoreId();
            $formModel = Mage::getModel('flexibleforms/flexibleforms')->getCollection()->addFilter('form_id', $row->getId())->getFirstItem();
            $urlKey = Mage::getUrl('flexibleforms').$formModel['form_url_key'].'.html';
            $url = '<a href="'.$urlKey.'" target="_blank">'.$this->__('Preview').'</a>';
            return $url;
    }
}