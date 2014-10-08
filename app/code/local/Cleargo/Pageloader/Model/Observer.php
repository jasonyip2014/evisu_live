<?php
/**
 * Created by PhpStorm.
 * User: Jason Yip
 * Date: 9/27/14
 * Time: 2:11 AM
 */

class Cleargo_Pageloader_Model_Observer {
    protected $_isNewPageLoad = false;

    public function newPageLoadBefore(Varien_Event_Observer $observer) {
        $is_page = $observer->getEvent()->getObserver()->getControllerAction()->getRequest()->getParam('page');
        if ($is_page) {
            $this->_isNewPageLoad = true;
        }
    }

    public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer) {
        if ($this->_isNewPageLoad) {
            $layout = $observer->getEvent()->getLayout();
            $layout->getUpdate()->addUpdate('<remove name="head" /><remove name="header" /><remove name="breadcrumbs" /><remove name="top_bar" /><remove name="footer" />');
            $layout->generateXml();
            //Mage::log("testing",null,'test20140927.log');
        }
    }
}