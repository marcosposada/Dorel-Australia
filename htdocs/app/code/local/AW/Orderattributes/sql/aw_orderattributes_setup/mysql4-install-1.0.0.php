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


$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/attribute')}` (
  `attribute_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `code` VARCHAR(255) NOT NULL ,
  `type` VARCHAR(255) NOT NULL ,
  `default_value` TEXT NULL ,
  `is_enabled` SMALLINT(5) UNSIGNED NOT NULL ,
  `store_ids` VARCHAR(255) NULL ,
  `customer_groups` VARCHAR(255) NULL ,
  `display_on` VARCHAR(255) NULL ,
  `show_in_block` INT UNSIGNED NOT NULL ,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `validation_rules` TEXT NOT NULL ,
  PRIMARY KEY (`attribute_id`) ,
  UNIQUE INDEX `code_UNIQUE` (`code` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/value_int')}` (
  `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `quote_id` INT UNSIGNED NOT NULL ,
  `value` INT NULL ,
  PRIMARY KEY (`value_id`) ,
  KEY `fk_aw_orderattributes_value_int_quote_id` (`quote_id`),
  INDEX `fk_aw_orderattributes_value_int_aw_orderattributes_at_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_value_int_sales_flat_quote`
    FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_value_int_aw_orderattributes_attr1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Value(INT) table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/value_varchar')}` (
  `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `quote_id` INT UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`value_id`) ,
  KEY `fk_aw_orderattributes_value_varchar_quote_id` (`quote_id`),
  INDEX `fk_aw_orderattributes_value_varchar_aw_orderattribute_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_value_varchar_sales_flat_quote`
    FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_value_varchar_aw_orderattributes_1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Value(VARCHAR) table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/value_text')}` (
  `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `quote_id` INT UNSIGNED NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`value_id`) ,
  KEY `fk_aw_orderattributes_value_text_quote_id` (`quote_id`),
  INDEX `fk_aw_orderattributes_value_text_aw_orderattributes_a_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_value_text_sales_flat_quote`
    FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_value_text_aw_orderattributes_att1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Value(TEXT) table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/value_date')}` (
  `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `quote_id` INT UNSIGNED NOT NULL ,
  `value` DATE NULL ,
  PRIMARY KEY (`value_id`) ,
  KEY `fk_aw_orderattributes_value_date_quote_id` (`quote_id`),
  INDEX `fk_aw_orderattributes_value_date_aw_orderattribut_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_value_date_sales_flat_quote`
    FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_value_date_aw_orderattributes1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Value(DATE) table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/option')}` (
  `option_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`option_id`) ,
  INDEX `fk_aw_orderattributes_option_aw_orderattributes_attri_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_option_aw_orderattributes_attribu1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Option table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/option_value')}` (
  `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `option_id` INT UNSIGNED NOT NULL ,
  `store_id` SMALLINT(5) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`value_id`) ,
  KEY `fk_aw_orderattributes_option_value_store_id` (`store_id`),
  INDEX `fk_aw_orderattributes_option_value_aw_orderattributes_idx` (`option_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_option_value_store_entity`
    FOREIGN KEY (`store_id`)
    REFERENCES `{$installer->getTable('core_store')}` (`store_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_option_value_aw_orderattributes_o1`
    FOREIGN KEY (`option_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/option')}` (`option_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Option Values table';

CREATE  TABLE IF NOT EXISTS `{$installer->getTable('aw_orderattributes/label')}` (
  `label_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `store_id` SMALLINT(5) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`label_id`) ,
  KEY `fk_aw_orderattributes_label_store_id` (`store_id`),
  INDEX `fk_aw_orderattributes_label_aw_orderattributes_attrib_idx` (`attribute_id` ASC) ,
  CONSTRAINT `fk_aw_orderattributes_label_store_entity`
    FOREIGN KEY (`store_id`)
    REFERENCES `{$installer->getTable('core_store')}` (`store_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aw_orderattributes_label_aw_orderattributes_attribute1`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `{$installer->getTable('aw_orderattributes/attribute')}` (`attribute_id` )
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Label table';
");

$installer->endSetup();