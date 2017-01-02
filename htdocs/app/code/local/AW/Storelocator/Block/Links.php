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


class AW_Storelocator_Block_Links extends Mage_Core_Block_Template
{
    public function addLocatorLink()
    {
        if ($this->helper('aw_storelocator')->extensionEnabled('AW_Storelocator')) {
            if (Mage::getStoreConfig('aw_storelocator/general/add_to_top')) {
                $identifier = $this->helper('aw_storelocator/config')->getUrlKey(Mage::app()->getStore()->getId());
                $parentBlock = $this->getParentBlock();
                $text = $this->__('Store Locations');

                if($parentBlock) {
                    $parentBlock->addLink(
                        $text, $identifier, $text, true, array(), 25, null, 'class="top-link-aw-storelocator"'
                    );
                }
            }
        }
        return $this;
    }
}