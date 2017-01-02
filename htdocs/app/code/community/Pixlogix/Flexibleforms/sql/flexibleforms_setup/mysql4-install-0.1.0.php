<?php

$installer = $this;
  
$installer->startSetup();
  
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('flexibleforms')};
CREATE TABLE {$this->getTable('flexibleforms')} (
 	`form_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
	`form_title` VARCHAR(255) NOT NULL,
	`form_url_key` VARCHAR(255) NOT NULL,
	`form_top_description` text NOT NULL ,
	`form_bottom_description` text NOT NULL ,
	`form_success_description` text NOT NULL ,
	`form_fail_description` text NOT NULL ,
	`form_button_text` VARCHAR(255) NULL,
	`form_status` INT(11) NOT NULL DEFAULT '2',
	`form_redirect_url` VARCHAR(255) NOT NULL,
	`enable_captcha` INT(11) NULL DEFAULT '0',
	`total_fields` INT(11) NULL DEFAULT '0',
	`enable_admin_email` INT(11) NOT NULL DEFAULT '1',
	`enable_customer_email` INT(11) NOT NULL DEFAULT '1',
	`form_created_time` DATETIME DEFAULT NULL,
	`form_update_time` DATETIME DEFAULT NULL,
	PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('flexibleforms_fields')};
CREATE TABLE {$this->getTable('flexibleforms_fields')} (
	`field_id` int(11) NOT NULL AUTO_INCREMENT,
	`form_id` int(11) NOT NULL,
	`title` varchar(255) NOT NULL,
	`type` varchar(100) NOT NULL,
	`field_note` varchar(255),
	`field_class` varchar(255),
	`options` int(11) NULL,
	`options_value` text,
	`field_title_to_email` int(11) NOT NULL,
	`position` int(11) NULL,
	`required` tinyint(1) NOT NULL,
	`created_time` datetime NOT NULL,
	`update_time` datetime NOT NULL,
	`status` INT(11) NOT NULL DEFAULT '2',
	PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('flexibleforms_result')};
CREATE TABLE {$this->getTable('flexibleforms_result')} (
	`result_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 	`form_id` INT(11) NOT NULL,
 	`value` text NULL,
	PRIMARY KEY (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();