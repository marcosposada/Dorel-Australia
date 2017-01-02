<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('flexibleforms')}
    ALTER `store_id` SET DEFAULT 0;
");
$installer->endSetup();