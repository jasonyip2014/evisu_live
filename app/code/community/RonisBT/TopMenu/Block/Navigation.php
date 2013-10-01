<?php

class RonisBT_TopMenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    /**
     * Store categories cache
     *
     * @var array
     */
    protected $_storeCategories = array();

    const XML_NODE_CATEGORY_DEFAULT_CONFIG = 'global/topmenu/category/default';

    protected $_default_data = null;
    protected $_menu_renderers = array();
    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => 3600,
            'cache_tags'        => array(Mage_Catalog_Model_Category::CACHE_TAG, Mage_Core_Model_Store_Group::CACHE_TAG),
        ));
    }
    /**
     * Get Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        if ($this->hasData('cache_key')) {
            return $this->getData('cache_key');
        }
        /**
         * don't prevent recalculation by saving generated cache key
         * because of ability to render single block instance with different data
         */
        $key = $this->getCacheKeyInfo();
        //ksort($key);  // ignore order
        $key = array_values($key);  // ignore array keys
        $key = implode('|', $key);
        $key = sha1($key);
        return 'CATALOG_NAVIGATION_'.$key;
    }

    public function getCacheKeyInfo()
    {
        $info = array();
        foreach ($this->_menu_renderers as $key=>$block){
            $info[$key] = array(get_class($block), $block->getTemplate());
        }
        $items = array(
            'renderers' => serialize($info)
        );
        $items = parent::getCacheKeyInfo() + $items;
        return $items;
    }


    public function getCurrenCategoryKey()
    {
        if (!$this->_currentCategoryKey){
            $category = Mage::registry('current_category');
            if ($category){
                $this->_currentCategoryKey = implode('/',array_slice(explode('/',$category->getPath()),0,2));
            } elseif ($this->getCurrentMenu()){
                $this->_currentCategoryKey = 'topmenu/'.$this->getCurrentMenu();
            } else {
                $this->_currentCategoryKey = '1/'.Mage::app()->getStore()->getRootCategoryId();
            }
        }
        return $this->_currentCategoryKey;
    }

    protected function getSubmenuHtml($item, $level=1)
    {
        $type = $item->getType();
        $block = $item->getBlock();
        $html = '';
        if ($type == 'block'){
            if ($block){
                $renderer = $this->getMenuRenderer($block);
                if (!is_null($renderer)){
                    $renderer->resetSubmenu()
                        ->setMenuItem($item)
                        ->setLevel($level);
                    $html .= $renderer->toHtml();
                }
            }
        } elseif ($type == 'category'){
            if ($block){
                $renderer = $this->getMenuRenderer($block);
                if ($renderer){
                    $renderer->resetSubmenu()
                        ->setMenuItem($item)
                        ->setLevel($level);
                    $html .= $renderer->toHtml();
                }
            } else {
                return $this->drawCategoryItem($item->getCategory(), $level);
            }
        } elseif ($type == 'menu'){
            return $this->drawSubmenu($item, $level);
        } else {
            // unknown item type
            Mage::log('[topmenu] Unknown top menu item type ' . $type);
        }
        return $html;
    }

    protected function getItemHtml($item, $level=0, $last=false, $customTitle = false)
    {
        if($customTitle)
        {
            $title = $customTitle;
        }
        else
        {
            $title = $this->__($item->getTitle());
        }

        $params = '';
        $url = $item->getUrl() ? $item->getUrl() : 'javascript:void(0);';

        $position = $this->_getItemPosition($level);
        $class = ' nav-'.$item->getKey();
        if ($level == 0){
            $class .= ' top-level';
        }

        if ($item->getCategory()){
            $class .= ' nav-id-'.$item->getCategory()->getId();
            if ($this->isCategoryActive($item->getCategory()))
                $class .= ' active';
        } else {
            if ($this->getCurrentMenu() == $item->getKey())
                $class .= ' active';
            else if ($item->getUrl() && Mage::helper('core/url')->getCurrentUrl() == $item->getUrl())
                $class .= ' active';
        }
        if ($last){
            $class .= ' last';
        }

        $submenu = $this->getSubmenuHtml($item, $level);
        if (!empty($submenu)){
            $class .= ' parent';
            if ($level!=0)
                $params = Mage::helper('topmenu')->getMenuJS();
        }
        return sprintf($this->helper('topmenu')->getItemTemplate(), $level, $position, $class, $params, $url, $title, $submenu);
    }

    public function drawCategoryItem($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()){
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildrenCategories();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren){
            $cnt = 0;
            $j = 0;
            foreach ($children as $child){
                if ($child->getIsActive())
                    $cnt++;
            }
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }
            if (!empty($htmlChildren)){
                $html.= '<ul class="level' . $level . '">'."\n"
                        .$htmlChildren
                        .'</ul>'."\n";
            }
        }
        return $html;
    }

    public function drawSubmenu($item, $level=0, $last=false)
    {
        $html = '';
        $children = $item->getChildren();
        $childrenCount = count($children);
        $hasChildren = $children && $childrenCount;
        if ($hasChildren){
            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child){
                $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $childrenCount);
            }
            if (!empty($htmlChildren)){
                $html.= '<ul class="level' . $level . '">'."\n"
                        .$htmlChildren
                        .'</ul>'."\n";
            }
        }
        return $html;
    }

    public function drawItem($item, $level=0, $last=false, $thumbnail = false)
    {
        if (!$this->isActive())
            return parent::drawItem($item, $level, $last);

        if ($item instanceof RonisBT_TopMenu_Model_Menu_Item)
            return $this->getItemHtml($item, $level, $last, $thumbnail);

        return parent::drawItem($item, $level, $last);
    }

    protected function _getDefaultData()
    {
        if (is_null($this->_default_data)){
            $node = Mage::getConfig()->getNode(self::XML_NODE_CATEGORY_DEFAULT_CONFIG);
            if ($node){
                $this->_default_data = $node->asArray();
            } else {
                $this->_default_data = array();
            }
        }
        return $this->_default_data;
    }

    public function getInactiveCategoryIds()
    {
        if (is_null($this->getData('_inactive_category_ids'))){
            $inactiveCateroryIds = array();
            $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
            $categoryModel = Mage::getModel('catalog/category');
            if ($categoryModel->checkId($rootCategoryId)){
                //
                // hack to exclude items marked as "include in menu" = no
                // bug in Magento: by default this attribute is ignored
                //
                $inactiveCaterories = $categoryModel->getCategories($rootCategoryId, 0, false, true, false)
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('level', 2)
                    ->addAttributeToFilter('is_active', 1)
                    ->addAttributeToFilter('include_in_menu', 0);
                foreach ($inactiveCaterories as $category){
                    $inactiveCateroryIds[] = $category->getId();
                }
            }
            $this->setData('_inactive_category_ids', $inactiveCateroryIds);
        }
        return $this->getData('_inactive_category_ids');
    }


    protected function _collectMenuItems()
    {
        $top = Mage::getModel('topmenu/menu_item');

        //$inactiveCateroryIds = $this->getInactiveCategoryIds();
        foreach ($this->getStoreCategories() as $category){
            //if (!in_array($category->getId(), $inactiveCateroryIds)){
                $item = Mage::getModel('topmenu/menu_item')
                    ->assignCategory($category, Mage::helper('topmenu')->getCategoryUrl($category))
                    ->addData($this->_getDefaultData());
                $top->addItem($item);
            //}
        }

        $keys = array();
        $items = Mage::helper('topmenu')->getItems();
        if (!empty($items)){
            foreach ($items as $item){
                $item = Mage::getModel('topmenu/menu_item')
                    ->setData($item);
                if ($item->getEnabled()){
                    $keys[$item->getKey()] = $item;
                }
            }
        }

        foreach ($keys as $key=>$item){
            $type = $item->getType();
            if ($type == 'category' || $type == 'block'){
                $top->addItem($item);
            } else {
                $parent = $item->getParent();
                if ($parent){
                    if (isset($keys[$parent])){
                        $keys[$parent]->addItem($item);
                    }
                } else {
                    $top->addItem($item);
                }
            }
        }
        $top->sortItems()->cleanup();
        return $top;
    }

    public function getMenuItems()
    {
        if (is_null($this->getData('_top_menu'))){
            $top = $this->_collectMenuItems();
            $this->setData('_top_menu', $top);
        }
        return $this->getData('_top_menu')->getChildren();
    }

    public function getMenuRenderer($type)
    {
        return isset($this->_menu_renderers[$type]) ? $this->_menu_renderers[$type] : null;
    }

    public function addMenuRenderer($type, $template, $block_type=null)
    {
        $block_type = empty($block_type) ? 'topmenu/submenu' : $block_type;
        $block = $this->getLayout()
            ->createBlock($block_type, $type, array('template'=>$template));
        $this->_menu_renderers[$type] = $block;
        return $this;
    }

    public function addMenuRendererBlock($type, $block)
    {
        $this->_menu_renderers[$type] = $block;
        return $this;
    }

    public function isActive()
    {
        return Mage::helper('topmenu')->isActive();
    }

    /**
     * Retrieve current store categories
     *
     * @param   boolean|string $sorted
     * @param   boolean $asCollection
     * @return  Varien_Data_Tree_Node_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection|array
     */
    public function getStoreCategories($sorted=false, $asCollection=false, $toLoad=true)
    {
        $parent     = Mage::app()->getStore()->getRootCategoryId();
        $cacheKey   = sprintf('topmenu-%d-%d-%d-%d-'.Mage::app()->getStore()->getId(), $parent, $sorted, $asCollection, $toLoad);
        Varien_Profiler::start('_storeCategories: '.__METHOD__);
        if (isset($this->_storeCategories[$cacheKey])/* || ($cache = Mage::app()->loadCache(Mage_Catalog_Model_Category::CACHE_TAG.$cacheKey))*/) {
            /*if (!isset($this->_storeCategories[$cacheKey]))
            {
                $this->_storeCategories[$cacheKey] = unserialize($cache);
            }*/
            Varien_Profiler::stop('_storeCategories: '.__METHOD__);
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         */
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        if (!$category->checkId($parent)) {
            if ($asCollection) {
                return new Varien_Data_Collection();
            }
            return array();
        }

        $recursionLevel  = max(0, (int) Mage::app()->getStore()->getConfig('catalog/navigation/max_depth'));
        $storeCategories = $category->getCategories($parent, $recursionLevel, $sorted, $asCollection, false);

        $this->_storeCategories[$cacheKey] = $storeCategories;
        //Mage::app()->saveCache(serialize($storeCategories), Mage_Catalog_Model_Category::CACHE_TAG.$cacheKey, array(Mage_Catalog_Model_Category::CACHE_TAG));

        return $storeCategories;
    }

    /**
     * Load block html from cache storage
     *
     * @return string | false
     */
    protected function _loadCache()
    {
        if (is_null($this->getCacheLifetime())) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $cacheData = Mage::app()->loadCache($cacheKey);
        if ($cacheData) {
            $cacheData = str_replace(
                $this->_getSidPlaceholder($cacheKey),
                $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
                $cacheData
            );
            if ($category = Mage::registry('current_category'))
            {
                $currentCategoryKey = explode('/',$category->getPath());
                if (count($currentCategoryKey)>2)
                {
                    $regexp = preg_quote('nav-id-'.$currentCategoryKey[2]);
                    $cacheData = preg_replace('/(?<=\s|")(' . $regexp . ')(?=\s|")/u', '$1 active', $cacheData);
                }
            }

        }
        return $cacheData;
    }

    /**
     * Save block content to cache storage
     *
     * @param string $data
     * @return Mage_Core_Block_Abstract
     */
    protected function _saveCache($data)
    {
        if (is_null($this->getCacheLifetime())) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $data = str_replace(
            $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
            $this->_getSidPlaceholder($cacheKey),
            $data
        );
        $classes = array();
        $classesCount = preg_match_all('/(?<=\s)class="([^"]*?active[^"]*?)"/u', $data, $classes); //RonisBT: fix regexp
        for ($i = 0; $i < $classesCount; $i++) {
            $classAttribute = $classes[0][$i];
            $classValue = $classes[1][$i];
            $classInactive = preg_replace('/(\s+active|active\s+|^active$)/', '', $classAttribute); //RonisBT: fix regexp
            $data = str_replace($classAttribute, $classInactive, $data);
        }
        Mage::app()->saveCache($data, $cacheKey, $this->getCacheTags(), $this->getCacheLifetime());
        return $this;
    }
}
