<?php

class RonisBT_TopMenu_Model_Observer
{
    public function addUrlKeyToCategoryCollection(Varien_Event_Observer $observer)
    {
        $select = $observer->getEvent()->getSelect();
        $select->columns('url_key','main_table');
    }
}
