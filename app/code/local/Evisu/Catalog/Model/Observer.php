<?php

class Evisu_Catalog_Model_Observer
{
    public function addAttributesToCategoryCollection(Varien_Event_Observer $observer)
    {
        $select = $observer->getEvent()->getSelect();
        // Add necessary attributes to category collection:
        $select->columns(array(
            'additional_promo_copy',
            'menu_thumbnail_image',
        ),'main_table');
    }
}