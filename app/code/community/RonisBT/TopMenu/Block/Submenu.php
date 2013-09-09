<?php

class RonisBT_TopMenu_Block_Submenu extends Mage_Core_Block_Template
{

    protected $_multiselect = false;

    public function resetSubmenu(){
        $this->unsetData('layer');
        return $this;
    }

    public function getCategory(){
        return $this->getMenuItem()->getCategory();
    }

    public function getCategoryUrl($category, $query=null){
        return Mage::helper('topmenu')->getCategoryUrl($category, $query);
    }

    public function getLayer(){
        $layer = $this->getData('layer');
        if (is_null($layer)){
            $layer = Mage::helper('topmenu')->getLayer($this->getCategory()->getId());
            $this->setData('layer', $layer);
        }
        return $layer;
    }

    public function getFilterItems($code, $filterModel='catalog/layer_filter_attribute'){
        return Mage::helper('topmenu')->getFilterItems($this->getLayer(), $code, $filterModel);
    }

    public function setMultiselectFiltersEnabled($enbled){
        $this->_multiselect = $enbled;
        return $this;
    }

    public function getFilterItemUrl($category, $item, $escape=false){
        return Mage::helper('topmenu')->getFilterItemUrl($category, $item, $escape, $this->_multiselect);
    }

}
