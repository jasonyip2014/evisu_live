<?php
//die('sorry');
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);

$installer->addAttributeGroup('catalog_category',$attributeSetId,'First Promo Panel', 40);
$attributeGroup   = $installer->getAttributeGroup('catalog_category',$attributeSetId,'First Promo Panel');
$attributeGroupId = $attributeGroup['attribute_group_id'];

$installer->addAttribute('catalog_category', 'first_promo_panel_title', array(
    'type'              => 'text',
    'frontend'          => '',
    'label'             => 'Title',
    'input'             => 'text',
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

$installer->addAttribute('catalog_category', 'first_promo_panel_enabled', array(
    'type'             => 'int',
    'frontend'         => '',
    'label'            => 'Enabled',
    'input'            => 'select',
    'class'            => '',
    'source'           => 'eav/entity_attribute_source_boolean',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'          => true,
    'required'         => false,
    'user_defined'     => true,
    'default'          => '0',
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => true,
    'unique'           => false,
));

$installer->addAttribute('catalog_category', 'first_promo_panel_image', array(
    'type'              => 'varchar',
    'backend'           => 'catalog/category_attribute_backend_image',
    'frontend'          => '',
    'label'             => 'Image',
    'input'             => 'image',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => true,
    'unique'            => false,
    //'notice'            => 'test text',
));

$installer->addAttribute('catalog_category', 'first_promo_panel_description', array(
    'type'              => 'text',
    'frontend'          => '',
    'label'             => 'Description',
    'input'             => 'textarea',
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

$installer->addAttribute('catalog_category', 'first_promo_panel_link_text', array(
    'type'              => 'text',
    'frontend'          => '',
    'label'             => 'Link Text',
    'input'             => 'text',
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

$installer->addAttribute('catalog_category', 'first_promo_panel_target_url', array(
    'type'              => 'text',
    'frontend'          => '',
    'label'             => 'Target Url',
    'input'             => 'text',
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
    'visible_on_front'  => false,
    'unique'            => false,
    //'notice'            => 'test text',
));
$attributes = array(
    'first_promo_panel_title',
    'first_promo_panel_enabled',
    'first_promo_panel_image',
    'first_promo_panel_description',
    'first_promo_panel_link_text',
    'first_promo_panel_target_url'
);
foreach($attributes as $index => $attribute)
    $installer->addAttributeToGroup(
        $entityTypeId,
        $attributeSetId,
        $attributeGroupId,
        $attribute,
        ($index + 1) * 10
    );

$installer->endSetup();
