<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('flexibleforms')}
        ADD COLUMN `admin_email_address` VARCHAR(100) NOT NULL AFTER `enable_admin_email`,
	ADD COLUMN `customer_reply_email` VARCHAR(100) NOT NULL AFTER `enable_customer_email`;
");

$installer->run("
	ALTER TABLE {$this->getTable('flexibleforms_fields')}
        ADD COLUMN `fieldset_id` int(11) NOT NULL AFTER `form_id`,
        ADD COLUMN `tooltip` TEXT NOT NULL AFTER `type`,
        ADD COLUMN `pre_define_var` varchar(50) NULL AFTER `field_class`,
        ADD COLUMN `layout` int(1) NOT NULL DEFAULT '1' AFTER `pre_define_var`,
        ADD COLUMN `allowed_max_size` varchar(10) NULL AFTER `field_class`,
	ADD COLUMN `allowed_ext` TEXT NULL AFTER `field_class`;
");

$installer->run("
	ALTER TABLE {$this->getTable('flexibleforms_result')}
        ADD COLUMN `sender_ip` varchar(20) NOT NULL AFTER `value`,
        ADD COLUMN `submit_time` varchar(30) NOT NULL AFTER `sender_ip`,
        ADD COLUMN `browser_info` varchar(255) NOT NULL AFTER `submit_time`;
");

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('flexibleforms_fieldset')};
CREATE TABLE {$this->getTable('flexibleforms_fieldset')} (
	`fieldset_id` int(11) NOT NULL AUTO_INCREMENT,
	`form_id` int(11) NOT NULL,
	`fieldset_title` varchar(255) NOT NULL,
	`fieldset_position` int(11) NOT NULL DEFAULT '10',
	`created_time` datetime NOT NULL,
	`update_time` datetime NOT NULL,
	`fieldset_status` INT(11) NOT NULL DEFAULT '2',
	PRIMARY KEY (`fieldset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();