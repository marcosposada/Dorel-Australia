<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('flexibleforms_result')}
    ADD COLUMN `product_id` int(10) AFTER `form_id`
");

$installer->endSetup();