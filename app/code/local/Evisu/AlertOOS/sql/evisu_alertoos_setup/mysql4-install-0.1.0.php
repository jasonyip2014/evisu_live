<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->installEntities();

$installer->startSetup();

$installer->addAttribute('alertoos', 'sku' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'SKU',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));

$installer->addAttribute('alertoos', 'first_name' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'First Name',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));

$installer->addAttribute('alertoos', 'last_name' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Last Name',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));

$installer->addAttribute('alertoos', 'email_address' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Email Address',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));

$installer->addAttribute('alertoos', 'telephone' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Telephone',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));


$installer->addAttribute('alertoos', 'created_at' , array(
    'type'              => 'datetime',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Created At',
    'input'             => 'date',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));


$installer->addAttribute('alertoos', 'store' , array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Store',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'frontend_class'    => '',
    'note'              => '',
));


$attributeSet = $installer->getAttributeSet('alertoos','Default');
$attributeSetId = $attributeSet['attribute_set_id'];

$attributeGroup = $installer->getAttributeGroup('alertoos',$attributeSetId,'General');
$attributeGroupId = $attributeGroup['attribute_group_id'];

$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'sku');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'first_name');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'last_name');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'email_address');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'telephone');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'created_at');
$this->addAttributeToSet('alertoos', $attributeSetId, $attributeGroupId, 'store');

$sql = "
INSERT INTO `{$this->getTable('adminforms_block')}` (`key`,`entity_type`,`entity_id`,`entity_grid_block`) values ('alertoos','alertoos',null,'evisu_alertoos/adminhtml_alertoos_grid');
";

$installer->run($sql);
$installer->endSetup();