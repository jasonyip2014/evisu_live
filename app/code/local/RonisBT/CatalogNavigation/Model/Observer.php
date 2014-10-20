<?php

class RonisBT_CatalogNavigation_Model_Observer
{
    protected $_data = array();
    protected $_isAjax = false;

    public function saveHtml($id,$transport)
    {
        $this->_data[$id] = $transport->getHtml();
    }

    public function getHtmls()
    {
        return $this->_data;
    }

    public function layerAjax(Varien_Event_Observer $observer)
    {
        if (Mage::helper('catalognavigation')->isAjaxEnabled() && (Mage::app()->getFrontController()->getAction() instanceof Mage_Catalog_CategoryController || Mage::app()->getFrontController()->getAction() instanceof Mage_CatalogSearch_ResultController) && $this->_isAjax)
        {
            $data = json_encode($this->getHtmls());
            $observer->getFront()->getResponse()->clearBody();
            $observer->getFront()->getResponse()->setBody($data);
        }
    }

    public function layerSave(Varien_Event_Observer $observer)
    {
        if (Mage::helper('catalognavigation')->isAjaxEnabled() && $observer->getBlock()->getRenderInAjax())
        {
            if ((Mage::app()->getFrontController()->getAction() instanceof Mage_Catalog_CategoryController || Mage::app()->getFrontController()->getAction() instanceof Mage_CatalogSearch_ResultController) && $this->_isAjax) {
                $this->saveHtml($this->processNames($observer->getBlock()->getNameInLayout()),$observer->getTransport());
            } else {
                $observer->getTransport()->setHtml('<div class="layer-ajax-container layer-ajax-'.$this->processNames($observer->getBlock()->getNameInLayout()).'">'.$observer->getTransport()->getHtml().'</div>');
            }
        }
    }

    public function processNames($name)
    {
        return str_replace('.','-',$name);
    }

    public function checkAjax(Varien_Event_Observer $observer)
    {
        $controller_action = $observer->getControllerAction();
        $is_page = $controller_action->getRequest()->getParam('page');
        Mage::dispatchEvent('cleargo_page_loading_before', array('observer' => $observer));
        if (Mage::helper('catalognavigation')->isAjaxEnabled() && $controller_action->getRequest()->isAjax() && $is_page = null)
        {
            $controller_action->getRequest()->setParam('isAjax',null);
            unset($_GET['isAjax']);
            $this->_isAjax = true;
        }
    }
}
