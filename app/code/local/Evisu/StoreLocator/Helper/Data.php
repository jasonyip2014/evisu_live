<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 10/7/13
 * Time: 2:38 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_StoreLocator_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getStoresCollection(RonisBT_Cms_Model_Page $page)
    {
        /* @var $page RonisBT_Cms_Model_Page */
        $collection = Mage::helper('adminforms')
            ->getCollection('store_locator_grid')
            ->addAttributeToFilter('page_id', $page->getId())
            ->setOrder('sl_position', 'asc');
        $storesCollection = array();
        foreach($collection as $store)
        {
            $storesCollection[$store->getSlCountry()][] = $store;
        }
        return $storesCollection;
    }
}