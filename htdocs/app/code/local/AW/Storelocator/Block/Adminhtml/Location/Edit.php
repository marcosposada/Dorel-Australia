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


class AW_Storelocator_Block_Adminhtml_Location_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'aw_storelocator';
        $this->_controller = 'adminhtml_location';
        
        $location = Mage::registry('aw_storelocator_location');        
        $state = $location->getState();
        
        $locJson = Zend_Json::encode($location->getData());
       
        $this->_formScripts[] = "
            
                 document.observe('dom:loaded', function() {  
                        $('location_billing:region_id').setAttribute('defaultValue',  '{$state}');
                        var billingRegionUpdater = new RegionUpdater(
                            'location_country', 'location_billing:region', 'location_billing:region_id',
                            {$this->helper('directory')->getRegionJson()}, undefined, 'location_zip'
                        );
                        disableCustomState();
                        Event.observe('location_country', 'change', function(event) {                        
                            disableCustomState();
                        });
                 });               

                function saveAndContinueEdit(url) {
                    editForm.submit(url.replace(/{{tab_id}}/ig, awraf_info_tabsJsTabs.activeTab.id));
                };
                
                function saveAndPrepareZoom() {
                    editForm.submit();
                };
              
                
                /* disable or enable custom state field */
                function disableCustomState() {
                  window.setTimeout(function() {
                       if(!$('location_billing:region').visible()) {
                           $('location_billing:region').disabled = true; 
                       }
                       else {
                        $('location_billing:region').disabled = false; 
                       }
                    }, 100);
                }
                /* init google map */
                var awGoogleMap = new awStoreLocatorGoogleMap(
                    {$this->helper('aw_storelocator')->getGoogleMapJson()}, $locJson
                );";

        parent::__construct();
    }

    public function getHeaderText()
    {
        $location = Mage::registry('aw_storelocator_location');
        if ($location->getId()) {
            if ($location->getTitle()) {
                return $this->__("Edit Location '%s'", $this->escapeHtml($location->getTitle()));
            }
            return $this->__("Edit Location #'%s'", $this->escapeHtml($location->getId()));
        } else {
            return $this->__('Create New Location');
        }
    }

    protected function _prepareLayout()
    {       
        $this->_removeButton('save');

        $this->_addButton(
            'save',
            array(
                 'label'   => $this->__('Save'),
                 'onclick' => 'saveAndPrepareZoom()',
                 'class'   => 'save'
            ), 5
        );

        $this->_addButton(
            'save_and_continue',
            array(
                 'label'   => $this->__('Save and Continue Edit'),
                 'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
                 'class'   => 'save'
            ), 10
        );
        parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            array(
                 '_current' => true,
                 'back'     => 'edit',
                 'tab'      => '{{tab_id}}'
            )
        );
    }

}
