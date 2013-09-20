<?php

class RonisBT_CatalogNavigation_Block_Search_Layer_Filter_Attribute extends Mage_CatalogSearch_Block_Layer_Filter_Attribute
{
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        if (in_array($this->getAttributeModel()->getAttributeCode(),array('color','color_filter')))
            $this->setTemplate('catalog/layer/filter_color.phtml');
        return $this;
    }

    public function getFilterType(){
        return 'attribute';
    }

    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }

}
