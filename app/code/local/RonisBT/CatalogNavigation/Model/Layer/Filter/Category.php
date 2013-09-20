<?php

class RonisBT_CatalogNavigation_Model_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{

    const SUBCATEGORIES_CACHE_KEY = 'LAYER_SUBCAT_';

    protected $_parentCategories;
    protected $_query;
    protected $_productCollection;

    /**
     * Applied Category
     *
     * @var Mage_Catalog_Model_Category
     */
    protected $_appliedCategories = array();

    /**
     * Apply category filter to layer
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filters = $request->getParam($this->getRequestVar());
        $this->setRequest($request);
        if (!is_array($filters))
            $filters = array($filters);
        $this->_appliedCategories = array();
        foreach($filters as $filter)
        {
            $this->_categoryId = $filter;
            $this->_appliedCategory = Mage::getModel('catalog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($filter);

            if ($this->_isValidCategory($this->_appliedCategory)) {
                $this->_appliedCategories[] = $this->_appliedCategory;

                $this->getLayer()->getState()->addFilter(
                    $this->_createItem($this->_appliedCategory->getName(), $filter)
                );
            }
        }
        if (count($this->_appliedCategories))
        {
            $this->select_for_count = clone $this->getLayer()->getProductCollection()->getSelect();
            $this->getLayer()->getProductCollection()
                ->setFlag('categories_filter',$this->_appliedCategories)
                ->addCategoryFilter($this->_appliedCategories[0]);
            $collection = $this->getLayer()->getProductCollection();
            if ($collection instanceof Meemee_Search_Model_Resource_Collection)
            {
                $category_ids = array();
                foreach($this->_appliedCategories as $_category)
                    $category_ids[] = $_category->getId();
                $collection->addFqFilter(array('category_ids' => $category_ids));
            }
        }
        Mage::helper('catalognavigation')->applyEmptyStatus($this, $filterBlock);
        return $this;
    }

    protected function _getRequestQuery(){
        if (is_null($this->_query)){
            if ($this->getRequest()){
                $this->_query = http_build_query($this->getRequest()->getQuery());
                if ($this->_query)
                    $this->_query = '?' . $this->_query;
            } else {
                $this->_query = '';
            }
        }
        return $this->_query;
    }

    protected function _checkOptionActive($value){
        $request = $this->getRequest();
        if (!$request)
            return false;
        $values = $this->getRequest()->getParam($this->getRequestVar());
        if (!$values)
            return false;
        if (!is_array($values))
            $values = array($values);
        return in_array($value, $values);
    }

    protected function _getItemsData()
    {
        if (!Mage::registry('current_category')){
            // categories in search

            if (count($this->_appliedCategories))
                $categoty   = $this->getCategory()->getParentCategory();
            else
                $categoty   = $this->getCategory();

            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $categoty->getChildrenCategories();
            $collection = clone $this->getLayer()->getProductCollection();
            if ($collection->hasFlag('categories_filter') && count($collection->getFlag('categories_filter'))){
                $fromPart = $collection->getSelect()->getPart(Zend_Db_Select::FROM);
                if (isset($fromPart['cat_index'])){
                    //$fromPart['cat_index']['joinCondition'] = $joinCond;
                    $conditions = explode(' AND ',$fromPart['cat_index']['joinCondition']);
                    foreach($conditions as $key => $condition){
                        if (strpos($condition,'(cat_index.category_id')!==false || strpos($condition,'cat_index.category_id')!==false || strpos($condition,'cat_index.is_parent')!==false)
                            unset($conditions[$key]);
                    }
                    $fromPart['cat_index']['joinCondition'] = join(' AND ',$conditions);
                    $collection->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
                }
            }
            $collection
                ->addCountToCategories($categories);

            $data = array();
            $standard = Mage::getStoreConfig('catalog_navigation/frontend/standard_layer_view');
            foreach ($categories as $category){
                if ($category->getIsActive() && $category->getProductCount()
                    && (!$standard || !$this->_checkOptionActive((string)$category->getId()))
                ){
                    $data[] = array(
                        'label' => Mage::helper('core')->htmlEscape($category->getName()),
                        'value' => $category->getId(),
                        'count' => $category->getProductCount(),
                    );
                }
            }
            $tags = $this->getLayer()->getStateTags();
            //$this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);

        } else {

            $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
            $data = $this->getLayer()->getAggregator()->getCacheData($key);

            if ($data === null) {
                $query = $this->_getRequestQuery();

                $category   = $this->getCategory();
                /** @var $category Mage_Catalog_Model_Categeory */
                $categories = $category->getChildrenCategories();

                $this->getLayer()->getProductCollection()
                    ->addCountToCategories($categories);

                $data = array();
                foreach ($categories as $category) {
                    if ($category->getIsActive() && $category->getProductCount()){
                        $data[] = array(
                            'label' => Mage::helper('core')->htmlEscape($category->getName()),
                            'value' => $category->getId(),
                            'count' => $category->getProductCount(),
                            'category_url' => $category->getUrl() . $query,
                        );
                    }
                }
                $tags = $this->getLayer()->getStateTags();
                $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
            }
        }
        return $data;
    }

    protected function _initItems()
    {
        if (!Mage::registry('current_category')){
            return parent::_initItems();
        } else {
            $data = $this->_getItemsData();
            $items=array();
            foreach ($data as $itemData){
                $items[] = Mage::getModel('catalog/layer_filter_item')
                    ->setFilter($this)
                    ->setLabel($itemData['label'])
                    ->setValue($itemData['value'])
                    ->setCount($itemData['count'])
                    ->setCategoryUrl($itemData['category_url']);
            }
            $this->_items = $items;
        }
        return $this;
    }

    public function getCategoryHierarchy(){
        if (is_null($this->_parentCategories)){
            $query = $this->_getRequestQuery();
            $parentIds = $this->getCategory()->getPathIds();
            $parentIds = array_splice($parentIds, 2);
            $this->_parentCategories = array();
            foreach ($parentIds as $categoryId){
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $this->_parentCategories[] = Mage::getModel('catalog/layer_filter_item')
                    ->setFilter($this)
                    ->setLabel($category->getName())
                    ->setValue($category->getId())
                    ->setCategoryUrl($category->getUrl() . $query);
            }
        }
        return $this->_parentCategories;
    }



    protected function _getProductCollection(){
        if (is_null($this->_productCollection)){
            $this->_productCollection = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToFilter('visibility', array('in'=>Mage::getModel('catalog/product_visibility')->getVisibleInSiteIds()))
                ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addPriceData(Mage::getSingleton('customer/session')->getCustomerGroupId(), Mage::app()->getStore()->getWebsiteId())
                ->applyFrontendPriceLimitations();
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($this->_productCollection);
        }
        return $this->_productCollection;
    }

    public function getCachedCategoryChildren($category){
        $categories = array();
        $cache_key = self::SUBCATEGORIES_CACHE_KEY
            . '_STORE_' . Mage::app()->getStore()->getId()
            . '_CAT_' . $category->getId()
            . '_CUSTGROUP_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
        if (Mage::app()->useCache('collections') && $cache = Mage::app()->loadCache($cache_key)){
            $categories = unserialize($cache);
        } else {
            $products = $this->_getProductCollection();

            $cache_tags = array(
                Mage_Catalog_Model_Category::CACHE_TAG.'_'.$category->getId()
            );

            if (Mage::helper('catalog/category_flat')->isEnabled()){
                $children = $category->getChildrenNodes();
                if (is_null($children)){
                    $children = $category->getChildrenCategories();
                }
            } else {
                $children = $category->getChildrenCategories();
            }
            //$children = $category->getChildrenCategories();
            $products->addCountToCategories($children);

            foreach ($children as $child){
                $cache_tags[] = Mage_Catalog_Model_Category::CACHE_TAG.'_'.$child->getId();
                if ($child->getIsActive() && $child->getProductCount()){
                    $categories[$child->getId()] = $child;
                }
            }
//            ksort($categories, SORT_LOCALE_STRING);
            $categories = array_values($categories);

            if (Mage::app()->useCache('collections')){
                Mage::app()->saveCache(serialize($categories), $cache_key, $cache_tags);
            }
        }
        return $categories;
    }

}
