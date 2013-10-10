<?php

class Evisu_Contacts_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_ENABLED   = 'evisu_contacts/contacts/enabled';

    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLED );
    }

    public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    public function getPage()
    {
        $storeId = Mage::app()->getStore()->getId();
        if(!$page = Mage::registry('contact_us_page_' . $storeId))
        {
            $page = Mage::getResourceModel('cmsadvanced/page_collection')
                ->addAttributeToSelect('*')
                ->addFieldToFilter('page_type', 'contact_us')
                ->setStoreId($storeId)
                ->getFirstItem();
            Mage::register('contact_us_page_' . $storeId, $page);
        }
        return $page;
    }
}
