<?php

$installer = $this;

$installer->startSetup();

// Foreign Key for Delete fields/child table value
$installer->getConnection()->addForeignKey(
	$installer->getFkName('flexibleforms/fields', 'form_id', 'flexibleforms', 'form_id'),
	$installer->getTable('flexibleforms/fields'),
	'form_id',
	$installer->getTable('flexibleforms'),
	'form_id'
);

// Foreign Key for Delete result/child table value
$installer->getConnection()->addForeignKey(
	$installer->getFkName('flexibleforms/result', 'form_id', 'flexibleforms', 'form_id'),
	$installer->getTable('flexibleforms/result'),
	'form_id',
	$installer->getTable('flexibleforms'),
	'form_id'
);

$installer->endSetup();