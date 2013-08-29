<?php
$installer = $this;
$installer->startSetup();

$installer->run('
ALTER TABLE `cms_page_entity` ADD `url_path` VARCHAR( 512 ) NOT NULL
');

$installer->endSetup();
