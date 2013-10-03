<?php
$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$installer->addAttribute('catalog_product','second_name', array(
    'type'                  => 'varchar',
    'input'                 => 'text',
    'label'                 => 'Second Name',
    'class'                 => '',
    'source'                => '',
    'global'                => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'               => true,
    'required'              => false,
    'user_defined'          => true,
    'default'               => '',
    'is_html_allowed_on_front' => true,
    'wysiwyg_enabled'       => true,
    'searchable'            => false,
    'filterable'            => false,
    'comparable'            => false,
    'visible_on_front'      => true,
    'unique'                => false,
    'used_in_product_listing' => true
));

$entityTypeId     = $installer->getEntityTypeId('catalog_product');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$installer->addAttributeToGroup($entityTypeId, $attributeSetId, 'General', 'second_name', 2);

$installer->endSetup();