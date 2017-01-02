<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('flexibleforms')}
    ADD COLUMN `enable_admin_email_template` int(3) NOT NULL DEFAULT '0' AFTER `admin_email_address`,
	ADD COLUMN `admin_email_template` varchar(255) NOT NULL AFTER `enable_admin_email_template`,
	ADD COLUMN `enable_customer_email_template` int(3) NOT NULL DEFAULT '0' AFTER `customer_reply_email`,
	ADD COLUMN `customer_email_template` varchar(255) NOT NULL AFTER `enable_customer_email_template`
");

$installer->run("
    ALTER TABLE {$this->getTable('flexibleforms_fields')}
    ADD COLUMN `field_value` varchar(255) NOT NULL AFTER `type`
");

$installer->run("
    ALTER TABLE {$this->getTable('flexibleforms_fieldset')}
    ADD COLUMN `fieldset_class` varchar(255) NOT NULL AFTER `fieldset_title`
");

$installer->endSetup();