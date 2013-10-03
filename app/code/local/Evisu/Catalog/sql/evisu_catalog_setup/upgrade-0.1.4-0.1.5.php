<?php
$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$installer->updateAttribute('catalog_product','weight', 'default_value' ,'0.0');

$installer->addAttribute('catalog_product','delivery', array(
    'group'                 => 'General',
    'type'                  => 'text',
    'input'                 => 'textarea',
    'label'                 => 'Delivery',
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

$installer->addAttribute('catalog_product','care', array(
    'group'                 => 'General',
    'type'                  => 'text',
    'input'                 => 'textarea',
    'label'                 => 'Care',
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

$installer->addAttribute('catalog_product','size_chart', array(
    'group'                 => 'General',
    'type'                  => 'text',
    'input'                 => 'textarea',
    'label'                 => 'Size Chart',
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

$installer->endSetup();