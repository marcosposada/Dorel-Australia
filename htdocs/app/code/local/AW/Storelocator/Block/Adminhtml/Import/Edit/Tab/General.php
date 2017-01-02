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


class AW_Storelocator_Block_Adminhtml_Import_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('import_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $this->__('Settings')));

        $fieldset->addField(
            'type', 'select',
            array(
                'label'   => $this->__('Import Type'),
                'title'   => $this->__('Import Type'),
                'name'    => 'import_type',
                'values' => array(
                    'locations' => $this->__('Locations'),
                    'hours' => $this->__('Opening Hours'),
                ),
            )
        );

        $fieldset->addField(
            'is_overwrite', 'select',
            array(
                'label'   => $this->__('Delete Existing'),
                'title'   => $this->__('Delete Existing'),
                'name'    => 'is_overwrite',
                'values' => array(
                    '1' => $this->__('Yes'),
                    '0' => $this->__('No'),
                ),
            )
        );

        $fieldset->addField(
            'file', 'file',
            array(
                 'label' => $this->__('Import File'),
                 'title' => $this->__('Import File'),
                 'name'  => 'import_file',
                 'after_element_html'  => '<p class="note"><span>' . $this->__('Use exported file from Grid page as example.'),
            )
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }

}
