<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Adminhtml product edit tabs
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class RonisBT_EditorialContent_Block_Adminhtml_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    protected $_attributeTabBlock = 'ronisbt_editorialcontent/adminhtml_catalog_product_edit_tab_attributes';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->addTab('pec_items', array(
            'label'     => Mage::helper('ronisbt_editorialcontent')->__('Editorial Content'),
            'url'   => $this->getUrl('*/*/bundles', array('_current' => true)),
            'class' => 'ajax',
        ));
        //$this->bindShadowTabs('pec_items', 'customer_options');
    }
}