<?php
class RonisBT_Cms_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('cmsadvanced', $this);

        $request = $front->getRequest();

        return $this->_matchRequest($request);
    }

    protected function _matchRequest($request)
    {
        // Fix for Full Page Cache.
        if ($request->isStraight()) {
            return;
        }
        
        $identifier = trim($request->getPathInfo(), '/');
                    
        $currentPage = null;

        if (empty($identifier) && $homePageId = Mage::helper('cmsadvanced')->getConfig()->getHomePageId()) {
            $currentPage = new Varien_Object();
            $currentPage->setId($homePageId);
        } elseif ($identifier) {
            $pages = Mage::getResourceModel('cmsadvanced/page_collection')
                   ->addFieldToFilter('url_path', $identifier)
                   ->setStoreId(Mage::app()->getStore()->getId())
                   ->setPage(1,1)
                   ;

            if (count($pages)) {
                $currentPage = $pages->getFirstItem();
            }
        }

        if (!$currentPage) {
            $currentPage = new Varien_Object();
            Mage::dispatchEvent('cmsadvanced_route_match', array('page' => $currentPage, 'request' => $request));
        }

        if ($currentPage && $currentPage->getId()) {
            $request->setModuleName('cmsadvanced')
            ->setControllerName('page')
            ->setActionName('view')
            ->setParam('id', $currentPage->getId())
            ->setParams((array) $currentPage->getParams())
            ;
            
            return true;
        }

        return false;
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        return $this->_matchRequest($request);
    }
}
?>
