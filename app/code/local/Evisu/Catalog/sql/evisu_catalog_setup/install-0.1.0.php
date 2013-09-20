<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
//$installer->removeAttribute('catalog_category', 'additional_promo_copy');
$installer->addAttribute('catalog_category', 'additional_promo_copy', array(
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Additional Promo Copy',
    'input'             => 'textarea',
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
));

$installer->addAttributeToGroup($entityTypeId, $attributeSetId, 'General Information', 'additional_promo_copy', 5);

$installer->endSetup();



