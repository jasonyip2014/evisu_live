<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);

$installer->addAttribute('catalog_category', 'menu_thumbnail_image', array(
    'type'              => 'varchar',
    'backend'           => 'catalog/category_attribute_backend_image',
    'frontend'          => '',
    'label'             => 'Menu thumbnail image',
    'input'             => 'image',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => true,
    'unique'            => false,
));


$installer->addAttributeToGroup($entityTypeId, $attributeSetId, 'General Information', 'menu_thumbnail_image', 9);

$installer->endSetup();
