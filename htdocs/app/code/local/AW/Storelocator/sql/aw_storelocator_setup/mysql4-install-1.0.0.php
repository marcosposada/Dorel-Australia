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


$this->startSetup();
try {
    $this->run("    
      create table if not exists {$this->getTable('aw_storelocator/location')} (
        `location_id` int(10) unsigned not null auto_increment,
        `status` tinyint(1) unsigned not null,
        `store_ids` text not null,
        `title` varchar(255) default null, 
        `description` text default null,
        `priority` int(10) unsigned not null,
        `country` varchar(255) default null, 
        `state` varchar(255) default null, 
        `city` varchar(255) default null, 
        `street` varchar(255) default null, 
        `zip` varchar(255) default null, 
        `phone` varchar(255) default null,
        `zoom` tinyint(4) unsigned not null, 
        `latitude` varchar(255) default null,
        `longtitude` varchar(255) default null,
        `image` varchar(255) default null,
        `custom_marker` varchar(255) default null,     
      PRIMARY KEY (`location_id`),
      KEY `AW_STORE_LOCATOR_PRIORITY` (`priority`),
      KEY `AW_STORE_LOCATOR_STATUS` (`status`)      
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;    
");
} catch (Exception $e) {
    Mage::logException($e);
}
$this->endSetup();

$options = Mage::getConfig()->getOptions();
$options->createDirIfNotExists($options->getMediaDir() . DS . 'aw_storelocator');

