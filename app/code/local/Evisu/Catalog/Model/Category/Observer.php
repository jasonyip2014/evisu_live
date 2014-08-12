<?php
class Evisu_Catalog_Model_Category_Observer{


    function redirectAlias(Varien_Event_Observer $observer){
        $category = $observer->getEvent()->getCategory();
        $alias_url = $category['alias_url'];
        $url_key = $category['url_key'];
        if ($alias_url && preg_match("/shop-all/i",$url_key)){
            $redirect = Mage::getBaseUrl().$alias_url;
            $response = Mage::app()->getFrontController()->getResponse();
            $response->setRedirect($redirect);
        }
    }

}