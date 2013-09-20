<?php

class RonisBT_CatalogNavigation_Block_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    protected function _prepareFilter()
    {
        if ($this->helper('catalognavigation')->isPriceSlider())
            $this->setTemplate('catalog/layer/filter_price_slider.phtml');
        return parent::_prepareFilter();
    }

    public function getFilterType(){
        return 'price';
    }

    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }

    public function getSliderUrlTemplate()
    {
        $query = array(
            $this->getRequestVar() => 'price_range_values',
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
    }
}
