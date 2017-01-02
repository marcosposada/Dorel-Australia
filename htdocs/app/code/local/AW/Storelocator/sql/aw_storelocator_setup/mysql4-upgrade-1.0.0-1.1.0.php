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

$installer = $this;
$installer->startSetup();
try {
    $installer->run("

        CREATE TABLE IF NOT EXISTS `{$installer->getTable('aw_storelocator/opening_hours')}` (
            `hour_id` int(10) unsigned NOT NULL auto_increment,
            `title` varchar(255) NOT NULL,
            `monday_is_open` tinyint(1) unsigned NOT NULL,
            `monday_opening_times` time NOT NULL,
            `monday_closing_times` time NOT NULL,
            `tuesday_is_open` tinyint(1) unsigned NOT NULL,
            `tuesday_opening_times` time NOT NULL,
            `tuesday_closing_times` time NOT NULL,
            `wednesday_is_open` tinyint(1) unsigned NOT NULL,
            `wednesday_opening_times` time NOT NULL,
            `wednesday_closing_times` time NOT NULL,
            `thursday_is_open` tinyint(1) unsigned NOT NULL,
            `thursday_opening_times` time NOT NULL,
            `thursday_closing_times` time NOT NULL,
            `friday_is_open` tinyint(1) unsigned NOT NULL,
            `friday_opening_times` time NOT NULL,
            `friday_closing_times` time NOT NULL,
            `saturday_is_open` tinyint(1) unsigned NOT NULL,
            `saturday_opening_times` time NOT NULL,
            `saturday_closing_times` time NOT NULL,
            `sunday_is_open` tinyint(1) unsigned NOT NULL,
            `sunday_opening_times` time NOT NULL,
            `sunday_closing_times` time NOT NULL,
            `status` tinyint(1) unsigned NOT NULL,
        PRIMARY KEY (`hour_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store Locator Opening Hours';

        CREATE TABLE IF NOT EXISTS `{$installer->getTable('aw_storelocator/opening_hours_location')}` (
            `hour_id` int(10) unsigned NOT NULL COMMENT 'Hour Id',
            `location_id` int(10) unsigned NOT NULL COMMENT 'Location Id',
        PRIMARY KEY (`hour_id`,`location_id`),
        CONSTRAINT `FK_AW_STORELOCATOR_OPENING_HOURS_LOCATION_TO_HOURS`
            FOREIGN KEY (`hour_id`)
            REFERENCES `{$installer->getTable('aw_storelocator/opening_hours')}` (`hour_id`)
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_AW_STORELOCATOR_OPENING_HOURS_LOCATION_TO_LOCATION`
            FOREIGN KEY (`location_id`)
            REFERENCES `{$installer->getTable('aw_storelocator/location')}` (`location_id`)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store Locator Locations and Opening Hours Relations';

    ");
} catch (Exception $e) {
    Mage::log($e->getTrace());
}

$this->endSetup();