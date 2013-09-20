<?php

class RonisBT_CatalogNavigation_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        /*if (in_array($this->getAttributeModel()->getAttributeCode(),array('color','colour'))) {
            $this->setTemplate('catalog/layer/filter_color.phtml');
        } else if (in_array($this->getAttributeModel()->getAttributeCode(), array('size'))) {
            $this->setTemplate('catalog/layer/filter_size.phtml');
        }*/
        return $this;
    }

    public function getFilterType(){
        return 'attribute';
    }

    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }
}
