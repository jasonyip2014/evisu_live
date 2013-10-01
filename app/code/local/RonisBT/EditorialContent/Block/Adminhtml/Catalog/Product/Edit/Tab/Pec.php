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

class RonisBT_EditorialContent_Block_Adminhtml_Catalog_Product_Edit_Tab_Pec extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_product = null;
    public function __construct()
    {

        parent::__construct();
        $this->setSkipGenerateContent(true);
        $this->setTemplate('pec/product/edit/pec.phtml');
    }

    public function getTabUrl()
    {
        //Mage::log($this->getUrl('*/pec_product_edit/form', array('_current' => true)), null ,'pec.log');
        return $this->getUrl('*/pec_product_edit/form', array('_current' => true));
    }

    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * Prepare layout
     *
     * @return Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle
     */
    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('ronisbt_editorialcontent')->__('Add New Editorial Content Item'),
                    'class' => 'add',
                    'id'    => 'add_new_option',
                    'on_click' => 'pecItem.add()'
                ))
        );

        $this->setChild('options_box',
            $this->getLayout()->createBlock('ronisbt_editorialcontent/adminhtml_catalog_product_edit_tab_pec_option',
                'adminhtml.catalog.product.edit.tab.pec.option')
        );

        return parent::_prepareLayout();
    }

    /**
     * Check block readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->getProduct()->getCompositeReadonly();
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }

    public function getFieldSuffix()
    {
        return 'product';
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }

    public function getTabLabel()
    {
        return Mage::helper('ronisbt_editorialcontent')->__('Editorial Content');
    }
    public function getTabTitle()
    {
        return Mage::helper('ronisbt_editorialcontent')->__('Editorial Content');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
}
