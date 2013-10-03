<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
//$installer = $this;

$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$installer->startSetup();

$installer->addAttribute('catalog_product','alt_small_image', array(
    'group'             => 'Images',
    'type'              => 'varchar',
    'frontend'          => 'catalog/product_attribute_frontend_image',
    'label'             => 'Alt Small Image',
    'input'             => 'media_image',
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
    'used_in_product_listing' => true
));

$installer->endSetup();