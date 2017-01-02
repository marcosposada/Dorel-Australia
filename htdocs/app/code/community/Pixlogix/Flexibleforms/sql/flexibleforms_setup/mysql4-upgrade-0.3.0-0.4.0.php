<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('flexibleforms')}
        ADD COLUMN  `store_id` VARCHAR(50) NOT NULL AFTER `form_id`;
");

$installer->run("
	ALTER TABLE {$this->getTable('flexibleforms_fields')}
        ADD COLUMN `form_star_max_value` int(11) NOT NULL AFTER `position`,
        ADD COLUMN `form_star_default_value` int(11) NOT NULL AFTER `form_star_max_value`,
        ADD COLUMN `custom_validation` varchar(255) NOT NULL AFTER `required`;
");
$installer->endSetup();