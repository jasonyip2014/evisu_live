<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 10/30/13
 * Time: 12:56 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_FitGuide_Helper_Data extends Mage_Core_Helper_Abstract {


    public function getMainNavigationItems()
    {
        if($main_page_id = Mage::getStoreConfig('evisu_fit_guide/fit_guide_config/main_page_id'))
        {
            $storeId = Mage::app()->getStore()->getId();
            if(!$items = Mage::registry('fit_guide_pages_' . $storeId))
            {
                $page = Mage::getResourceModel('cmsadvanced/page_collection')
                    ->addAttributeToSelect('*')
                    ->addFieldToFilter('entity_id', $main_page_id)
                    ->setStoreId($storeId)
                    ->getFirstItem();
                /* @var $page RonisBT_Cms_Model_Page */
                $items = $page->getChildren();

                Mage::register('fit_guide_pages_' . $storeId, $items);
            }
            return $items;
        }
        return false;
    }

    public function getProductUrl($fitAttribureId)
    {
        $url = null;
        $storeId = Mage::app()->getStore()->getId();

        $itemParentId = Mage::getResourceModel('cmsadvanced/page_collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('page_type', 'fit_guide_item')
            ->addFieldToFilter('fit_attribute', $fitAttribureId)
            ->setStoreId($storeId)
            ->getFirstItem()
            ->getParentId();

        if($itemParentId)
        {
            $url = Mage::getResourceModel('cmsadvanced/page_collection')
                ->addAttributeToSelect('*')
                ->addFieldToFilter('page_type', 'fit_guide_page')
                ->addFieldToFilter('entity_id', $itemParentId)
                ->setStoreId($storeId)
                ->getFirstItem()
                ->getUrl();
        }
        return $url;
    }
}