<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 10/8/13
 * Time: 3:58 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_StaticContent_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getNavigationItems()
    {
        $storeId = Mage::app()->getStore()->getId();
        if(!$items = Mage::registry('contact_us_page_' . $storeId))
        {
            $page = Mage::getResourceModel('cmsadvanced/page_collection')
                ->addAttributeToSelect('*')
                ->addFieldToFilter('page_type', 'static_content_main_page')
                ->setStoreId($storeId)
                ->getFirstItem();
            /* @var $page RonisBT_Cms_Model_Page */
            $items = $page->getChildren()->addFieldToFilter('include_in_menu', 1);

            Mage::register('contact_us_page_' . $storeId, $items);
        }
        return $items;
    }
}