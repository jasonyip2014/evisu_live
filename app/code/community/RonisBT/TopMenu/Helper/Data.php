<?php

class RonisBT_TopMenu_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_item_template = '<li class="level%s nav-%s%s"%s><a href="%s"><span>%s</span></a>%s</li>';
    //level0 nav-1 level-top first last parent active

    protected $_categoryInstance;
    protected $_layers = array();
    protected $_layerAttributes = array();
    protected $_cmsMenuItems = null;

    public function isActive(){
        return (bool) Mage::getStoreConfigFlag('design/topmenu/enabled');
    }

    public function getItemTemplate(){
        $template = (string)Mage::getStoreConfig('design/topmenu/item_template');
        return $template ? $template : $this->_item_template;
    }

    public function getMenuJS(){
        $js = (string)Mage::getStoreConfig('design/topmenu/javascript');
        return strlen($js) ? ' '.$js : '';
    }

    public function getItems(){
        return unserialize((string)Mage::getStoreConfig('design/topmenu/items'));
    }

    public function getUrlWrapper($url, $params=array()){
        if (strpos($url, '?') !== false){
            list($url, $query) = explode('?', $url, 2);
            if (strpos($query, '#') !== false){
                list($query, $hash) = explode('#', $query, 2);
                $params['_fragment'] = $hash;
            }
            $params['_query'] = $query;
            $params['_direct'] = true;
        }
        return Mage::getUrl($url, $params);
    }

    public function getCategoryChildren($category, $sorted=true, $additional=null){
        $categories = array();
        if (Mage::helper('catalog/category_flat')->isEnabled()){
            $children = $category->getChildrenNodes();
            if (is_null($children)){
                $children = $category->getChildrenCategories();
            }
        } else {
            $children = $category->getChildren();
        }
        foreach ($children as $child){
            if ($child->getIsActive()){
                //by cleargo, skip evisu/shop-all, womens/shop-all
                if (!preg_match("@(evisu/shop-all|womens/shop-all)@i",$child['request_path'])){
                    $categories[$child->getName()] = $child;
                }
            }
        }
        if ($additional && is_array($additional)){
            $categories = array_merge($categories, $additional);
        }
        if ($sorted){
            ksort($categories, SORT_LOCALE_STRING);
        }

        $categories = array_values($categories);
        return $categories;
    }

    protected function _getCategoryInstance(){
        if (is_null($this->_categoryInstance)){
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }

    public function getCategoryUrl($category, $query=null){
        if (!($category instanceof Mage_Catalog_Model_Category)){
            $category = $this->_getCategoryInstance()
                ->setData($category->getData());
        }
        if ($category->getAliasUrl()){
            $url = explode('?', $category->getAliasUrl(), 2);
            parse_str(isset($url[1]) ? $url[1] : '', $alias_query);
            if (!is_array($query)){
                $query = array();
            }
            $query = array_merge($alias_query, $query);
            $url = Mage::getUrl($url[0], array('_query'=>$query));
        } else {
            $url = $category->getUrl();
            if (!empty($query)){
                $url .= '?'. http_build_query($query);
            }
        }
        return $url;
    }

    public function getLayer($categoryId){
        if (!isset($this->_layers[$categoryId])){
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $layer = Mage::getModel('catalog/layer')
                ->setData('current_category', $category)
                ->apply();
            $this->_layers[$categoryId] = $layer;
        }
        return $this->_layers[$categoryId];
    }

    public function getLayerAttributes($layer){
        if (!isset($this->_layerAttributes[$layer->getStateKey()])){
            $this->_layerAttributes[$layer->getStateKey()] = $layer->getFilterableAttributes();
        }
        return $this->_layerAttributes[$layer->getStateKey()];
    }

    public function getFilterItems($layer, $code, $filterModel='catalog/layer_filter_attribute'){
        foreach ($this->getLayerAttributes($layer) as $filter){
            if ($filter->getAttributeCode() == $code){
                return Mage::getModel($filterModel)
                    ->setLayer($layer)
                    ->setAttributeModel($filter)
                    ->getItems();
            }
        }
        return array();
    }

    public function getFilterItemUrl($category, $item, $escape=false, $multiselect=false){
        $suffix = $multiselect ? '[0]' : '';
        $query = Mage::getModel('core/url')->setQueryParams(array(
            $item->getFilter()->getRequestVar() . $suffix => $item->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        ));
        return $this->getCategoryUrl($category) . '?' . $query->getQuery($escape);
    }

    public function processMenuHtml($block, $name='topMenu', $navName='catalog.topnav'){
        $html = $block->getChildHtml($name);
        if ($html){
            $nav = $block->getChild($name)->getChild($navName);
            if ($nav){
                foreach ($nav->getTopMenuCurrentKeys() as $key){
                    $html = preg_replace('/'.$key.'/', 'active', $html);
                }
                $html = preg_replace('/topmenu-key-[^" ]+/', '', $html);
            }
        }
        return $html;
    }

    /**
     * Retrieve image URL
     *
     * @return string
     */
    public function getMenuThumbnailImageUrl($image)
    {
        $url = '';

        if ($image) {
            $url = Mage::getBaseUrl('media').'catalog/category/'.$image;
        }

        return $url;
    }

    public function getCmsMenuItems()
    {
        if(!$this->_cmsMenuItems)
        {
            $this->_cmsMenuItems = Mage::getModel('cmsadvanced/page')
                ->getCollection()->addAttributeToSelect('*')
                ->addFieldToFilter('parent_id',Mage::getStoreConfig('design/topmenu/advanced_cms_root_id'))
                ->addFieldToFilter('is_active',1)
                ->addFieldToFilter('include_in_menu',1)
                ->setOrder('position', 'asc');
        }
        return $this->_cmsMenuItems;
    }

    public function getDefaultCategoryImage()
    {
        $image = Mage::getModel('catalog/category')
            ->load(Mage::getStoreConfig('design/topmenu/root_category_id'))
            ->getMenuThumbnailImage();

        return $image;
    }

}
