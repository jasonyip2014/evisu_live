<?php

class RonisBT_TopMenu_Model_Menu_Item extends Mage_Core_Model_Abstract
{

    protected $_first;
    protected $_last;

    protected $_keys = array();
    protected $_children_cache;
    protected $_sorted = false;

    protected $_categoryInstance;

    public function __toString()
    {
        $str = '';
        $current = $this->getItem($this->_first);
        while ($current){
            $data = $current->getData();
            unset($data['category']);
            $str .= print_r($data, true);
            $current = $this->getItem($current->getNext());
        }
        /*$str = $this->_first . "\n";
        foreach ($this->_keys as $key=>$item)
        {
            $data = $item->getData();
            unset($data['category']);
            $str .= "\n\n".print_r($data, true);
        }*/
        return (string)$str;
    }

    public function getItem($key)
    {
        return (!empty($key) && isset($this->_keys[$key])) ? $this->_keys[$key] : null;
    }

    protected function _insertBefore($item, $sibling=null)
    {
        if (is_null($sibling)){
            $last = $this->getItem($this->_last);
            if (!is_null($last))
                $last->setNext($item->getKey());
            $item->setPrevious($this->_last)
                ->setIsSorted(true);
            $this->_last = $item->getKey();
            if (is_null($this->_first))
                $this->_first = $item->getKey();
        } else {
            $prev = $this->getItem($sibling->getPrevious());
            $sibling->setPrevious($item->getKey());
            $item->setNext($sibling->getKey())
                ->setPrevious(is_null($prev) ? null : $prev->getKey())
                ->setIsSorted(true);
            if ($prev){
                $prev->setNext($item->getKey());
            } else {
                $this->_first = $item->getKey();
            }
        }
        return $this;
    }

    protected function sortItem($item)
    {
        if (!$item->getIsSorted()){
            if ($item->getSibling()){
                $sibling = $this->getItem($item->getSibling());
                if (!is_null($sibling)){
                    $this->_insertBefore($item, $sibling);
                }
            } else {
                $item->setIsSorted(true);
            }
        }
        return $this;
    }

    protected function detachSorted($item)
    {
        if ($item->getIsSorted()){
            $prev = $this->getItem($item->getPrevious());
            $next = $this->getItem($item->getNext());
            if ($prev){
                $prev->setNext($next->getKey());
            } else {
                $this->_first = $next->getKey();
            }
            if ($next){
                $next->setPrevious($prev ? $prev->getKey() : null);
            } else {
                $this->_last = $prev ? $prev->getKey() : null;
            }
            $item->setIsSorted(false);
        }
        return $this;
    }

    public function formatUrl($url=null, $url_rewrite=null)
    {
        if (is_null($url)){
            if ($this->getData('_direct_url'))
                return $this;
            $url = $this->getUrl();
        }
        $url = trim($url);
        if ($url == '#'){
            $this->setData('url', 'javascript:void(0)')
                ->setData('_direct_url', true)
                ->setData('_remote_url', true);
        } elseif (preg_match('#^(https?://|javascript:)#', $url)){
            $this->setData('url', $url)
                ->setData('_direct_url', true)
                ->setData('_remote_url', $this->isRemoteUrl($url));
        } else {
            if (is_null($url_rewrite))
                $url_rewrite = $this->getUrlRewrite();
            if ($url_rewrite){
                $this->setUrl(Mage::helper('topmenu')->getUrlWrapper($url));
                $this->setData('_remote_url', $this->isRemoteUrl($this->getUrl()));
            } else {
                $this->setUrl(Mage::getBaseUrl() . $url);
                $this->setData('_remote_url', false);
            }
            $this->setData('_direct_url', true);
        }
        return $this;
    }

    public function isRemoteUrl($url)
    {
        $baseUrl = Mage::getBaseUrl();
        $secureUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true);
        $innerUrl = (strncmp($url, $baseUrl, strlen($baseUrl)) == 0)
            || (strncmp($url, $secureUrl, strlen($secureUrl)) == 0);
        return !$innerUrl;
    }

    public function addItem($item, $overwrite=true)
    {
        $key = $item->getKey();
        $old = $this->getItem($key);
        if (!is_null($old)){
            if ($overwrite){
                foreach ($item->getData() as $name=>$value){
                    if (!empty($value)){
                        $old->setData($name, $value);
                    }
                }
                $sibling = $item->getSibling();
                if (!empty($sibling)){
                    $this->detachSorted($item);
                }
                $url = $item->getUrl();
                if (!empty($url)){
                    $old->setData('_direct_url', false)
                        ->formatUrl();
                }
            }
        } else {
            $this->_keys[$key] = $item;
            $sibling_key = $item->getSibling();
            if (empty($sibling_key)){
                $this->_insertBefore($item, null);
            } else {
                $sibling = $this->getItem($sibling_key);
                if (!is_null($sibling)){
                    $this->_insertBefore($item, $sibling);
                }
            }
            $url = $item->getUrl();
            if (!empty($url)){
                $item->formatUrl();
            }
        }
        $this->sortItem($item);
        if (!$item->getIsSorted())
            $this->_sorted = false;
        $this->_children_cache = null;
        return $this;
    }

    public function removeItem($item)
    {
        $key = $item->getKey();
        $old = $this->getItem($key);
        if (!is_null($old)){
            $this->detachSorted($old);
        }
        return $this;
    }


    protected function _getCategoryObject($node)
    {
        if (is_null($this->_categoryInstance)){
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance->setData($node->getData());
    }

    public function assignCategory($node, $url=null)
    {
        $category = $this->_getCategoryObject($node);
        return $this->setData(array(
            'type' => 'category',
            //'key' => $category->getUrlKey(),
            // RonisBT updated for EE 1.13, see Enterprise_Catalog_Model_Category_Url
            'key' => $category->getRequestPath(),
            'url' => is_null($url) ? $category->getUrl() : $url,
            'title' => $category->getName(),
            'url_rewrite' => false,
            'enabled' => true,
            'category' => $node,
            '_direct_url' => true,
        ));
    }

    public function isSorted()
    {
        return $this->_sorted;
    }

    public function sortItems()
    {
        if (!$this->_sorted){
            $this->_sorted = true;
            foreach ($this->_keys as $item){
                $this->sortItem($item);
                if (!$item->getIsSorted())
                    $this->_sorted = false;
            }
        }
        return $this;
    }

    public function cleanup()
    {
        foreach ($this->_keys as $key=>$item){
            if ($item->getType() == 'category' && is_null($item->getCategory())){
                $this->detachSorted($item);
            }
        }
    }

    public function getChildren(){
        if (is_null($this->_children_cache)){
            $this->sortItems();
            $cache = array();
            $current = $this->getItem($this->_first);
            while ($current){
                $cache[] = $current;
                $current = $this->getItem($current->getNext());
            }
            $this->_children_cache = $cache;
        }
        return $this->_children_cache;
    }

    public function isActive(){
        //return (trim(Mage::getUrl(trim(Mage::app()->getRequest()->getPathInfo(), '/')), '/') == trim($this->getUrl(), '/'));
        return false;
    }

}
