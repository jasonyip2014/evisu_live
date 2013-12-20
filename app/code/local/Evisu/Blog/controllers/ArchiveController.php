<?php
class Evisu_Blog_ArchiveController extends Mage_Core_Controller_Front_Action
{
    public function calendarAction()
    {
        $calendarHtml='';
        $calendarBlock = Mage::getSingleton('core/layout')->createBlock('evisu_blog/archive');
        $cache = Mage::app()->getCache()->load($calendarBlock->getCacheKey());
        if(!$cache)
        {
            $calendarHtml = $calendarBlock->setTemplate('wordpress/archive.phtml')->renderView();
            Mage::app()->getCache()->save($calendarHtml, $calendarBlock->getCacheKey());
        }
        else
        {
            $calendarHtml = $cache;
        }
        echo $calendarHtml;
        exit;
    }
}
