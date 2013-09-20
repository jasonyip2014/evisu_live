<?php
class RonisBT_CatalogNavigation_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_inverseFilterAttributes;

    public function getMaxPriceFilterRange(){
        return (int)Mage::getStoreConfig('catalog/catalog_navigation/max_price_filter_range');
    }

    public function isStandardLayout(){
        return (int)Mage::getStoreConfig('catalog/catalog_navigation/standard_layer_view');
    }

    public function isPriceSlider(){
        return (int)Mage::getStoreConfig('catalog/catalog_navigation/price_slider');
    }

    public function isAjaxEnabled(){
        return (int)Mage::getStoreConfig('catalog/catalog_navigation/use_ajax');
    }
    

    public function isExclusiveFilters(){
        return (int)Mage::getStoreConfig('catalog/catalog_navigation/exclusive_filters');
    }

    public function applyEmptyStatus($filter, $filterBlock){
        $query = array($filter->getRequestVar()=>$filter->getResetValue());
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $filterBlock->setEmptyUrl(Mage::getUrl('*/*/*', $params));

        $values = $filter->getRequest()->getParam($filter->getRequestVar());
        if (!count($values))
            $filterBlock->setIsEmptyActive(true);
        else
            $filterBlock->setIsEmptyActive(false);
    }

    public function getInverseFilterAttributes(){
        if (is_null($this->_inverseFilterAttributes)){
            $attrCodes = Mage::getStoreConfig('catalog/catalog_navigation/inverse_filters');
            $this->_inverseFilterAttributes = array_map('trim', explode(',', $attrCodes));
        }
        return $this->_inverseFilterAttributes;
    }

}
