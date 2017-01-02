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


class AW_Storelocator_Block_Adminhtml_Location_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $location = Mage::registry('aw_storelocator_location');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('location_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $this->__('General Information')));

        $fieldset->addField(
            'status', 'select',
            array(
                 'label'   => $this->__('Enable'),
                 'title'   => $this->__('Enable'),
                 'name'    => 'status',
                 'options' => array(
                     '1' => $this->__('Yes'),
                     '0' => $this->__('No'),
                 ),
            )
        );

        $fieldset->addField(
            'title', 'text',
            array(
                 'label' => $this->__('Title'),
                 'title' => $this->__('Title'),
                 'name'  => 'title',
                 'class'     => 'required-entry',
                 'required'  => true,
            )
        );

        $fieldset->addField(
            'street', 'text',
            array(
                 'label'    => $this->__('Street Address'),
                 'title'    => $this->__('Street Address'),
                 'name'     => 'street',
                 'required' => true
            )
        );

        $fieldset->addField(
            'city', 'text',
            array(
                 'label'    => $this->__('City'),
                 'title'    => $this->__('City'),
                 'name'     => 'city',
                 'required' => true
            )
        );

        $fieldset->addField(
            'phone', 'text',
            array(
                 'label' => $this->__('Telephone'),
                 'title' => $this->__('Telephone'),
                 'name'  => 'phone'
            )
        );

        $fieldset->addField(
            'zip', 'text',
            array(
                 'label' => $this->__('Postal / Zip Code'),
                 'title' => $this->__('Postal / Zip Code'),
                 'name'  => 'zip',
                 'class' => 'validate-zip-international'
            )
        );

//        $fieldset->addField(
//            'zoom', 'hidden',
//            array(
//                 'name' => 'zoom'
//            )
//        );

        $fieldset->addField(
            'country', 'select',
            array(
                 'label'   => $this->__('Country'),
                 'name'    => 'country',
                 'options' => Mage::getSingleton('aw_storelocator/source_country')->toFlatArray()
            )
        );

        $fieldset->addField(
            'state', 'label',
            array(
                 'label' => $this->__('Region'),
                 'name'  => 'state',
                 'state' => $location->getState()
            )
        )->setRenderer($this->getLayout()->createBlock('aw_storelocator/adminhtml_location_edit_renderer_region'));

        if (Mage::app()->isSingleStoreMode()) {
            $location->setStoreIds(0);
            $fieldset->addField(
                'store_ids', 'hidden',
                array(
                     'name' => 'store_ids[]'
                )
            );
        } else {
            $fieldset->addField(
                'store_ids', 'multiselect',
                array(
                     'name'     => 'store_ids[]',
                     'label'    => $this->__('Store view'),
                     'title'    => $this->__('Store view'),
                     'required' => true,
                     'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                )
            );
        }

        $fieldset->addField(
            'description', 'textarea',
            array(
                 'label' => $this->__('Description'),
                 'title' => $this->__('Description'),
                 'name'  => 'description'
            )
        );

        $fieldset->addField(
            'priority', 'text',
            array(
                 'label' => $this->__('Priority'),
                 'title' => $this->__('Priority'),
                 'name'  => 'priority',
                 'note'  => $this->__('Location with the greatest priority is displayed active by default')
            )
        );

        $fieldset->addField(
            'image', 'image',
            array(
                 'label' => $this->__('Store Image'),
                 'name'  => 'image',
                 'note'  => $this->__(
                     'Allowed file extensions are jpg, jpeg, gif, png. <br /> Recommended image size: 80x80 px'
                 )
            )
        );

        $fieldset->addField(
            'custom_marker', 'image',
            array(
                 'label' => $this->__('Custom Map Location Icon'),
                 'name'  => 'custom_marker',
                 'note'  => $this->__(
                     'Allowed file extensions are jpg, jpeg, gif, png. <br /> Recommended icon size is 20x30 px'
                 )
            )
        );

        $fieldsetGoogleMaps = $form->addFieldset('maps_fieldset', array('legend' => $this->__('Google Maps Settings')));

        $fieldsetGoogleMaps->addField(
            'zoom', 'text',
            array(
                'label' => $this->__('Zoom'),
                'title' => $this->__('Zoom'),
                'name' => 'zoom'
            )
        );

        $fieldsetGoogleMaps->addField(
            'latitude', 'text',
            array(
                'label' => $this->__('Latitude'),
                'title' => $this->__('Latitude'),
                'name'  => 'latitude'
            )
        );

        $fieldsetGoogleMaps->addField(
            'longtitude', 'text',
            array(
                'label' => $this->__('Longtitude'),
                'title' => $this->__('Longtitude'),
                'name'  => 'longtitude'
            )
        );

        $fieldsetGoogleMaps->addField(
            'google_map', 'label',
            array(
                 'label'      => $this->__('Google Map'),
                 'name'       => 'google_map',
                 'latitude'   => $location->getLatitude(),
                 'longtitude' => $location->getLongtitude()
            )
        )->setRenderer($this->getLayout()->createBlock('aw_storelocator/adminhtml_location_edit_renderer_map'));

        $form->setValues($location->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
