<?php
$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category', 'alias_url', array(
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Category alias URL',
    'note'              => 'If set will replace category default URL',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => true,
    'unique'            => false,
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'alias_url',
    '100'
);

$attributeId = $installer->getAttributeId($entityTypeId, 'alias_url');

$installer->endSetup();
