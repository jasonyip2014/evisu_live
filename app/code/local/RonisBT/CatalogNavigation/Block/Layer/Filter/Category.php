<?php

class RonisBT_CatalogNavigation_Block_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category
{

    public function __construct(){
        parent::__construct();
        if (Mage::registry('current_category'))
        {
            $this->setTemplate('catalog/layer/category.phtml');
            // used default.
            //$this->setTemplate('catalog/layer/filter.phtml');
        }
    }

    public function getFilterType(){
        return 'category';
    }

    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }

    public function isCurrentCategory($item){
        return $this->_filter->getCategory()->getId() == $item->getValue();
    }

    public function getCategoryHierarchy(){
        return $this->_filter->getCategoryHierarchy();
    }

    public function getItemsSorted(){
        $items = array();
        foreach ($this->getItems() as $item){
            $items[$item->getLabel()] = $item;
        }
        ksort($items, SORT_LOCALE_STRING);
        return array_values($items);
    }


    public function _getBaseCategory($category)
    {
        $parentIds = $category->getParentIds();
        if (count($parentIds) >= 3){
            $baseId = $parentIds[2];
            if ($baseId)
                $category = Mage::getModel('catalog/category')->load($baseId);
        }
        return $category;
    }

    /**
     * MeeMee-specific: base category hierarchy
     */
    public function getCategoryData($category){
        $result = new stdClass;
        $result->current = null;


        //$result->base_category = $this->helper('ronisbtcatalogswatches')->getBaseCategory($category);
        $result->base_category = $this->_getBaseCategory($category);
        $result->categories = $this->_filter->getCachedCategoryChildren($result->base_category);

        $parentIds = $category->getPathIds();
        $parentIds = array_splice($parentIds, 2);
        $count = count($result->categories);
        if (!empty($parentIds)){
            for ($i = 0; $i < $count; $i++){
                $child = $result->categories[$i];
                if (in_array($child->getId(), $parentIds)){
                    $result->current = $child;
                    break;
                }
            }
        }
        if ($result->current){
            $result->children = $this->_filter->getCachedCategoryChildren($result->current);
        } else {
            $result->children = null;
        }
        return $result;
    }

    /**
     * Retrieve filter items count
     *
     * @return int
     */
    public function getItemsCount()
    {
        return 1;
    }
}
