<?php

class RonisBT_CatalogNavigation_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{

    protected function getFilterRequestValues(){
        $request = $this->getFilter()->getRequest();
        if (!$request)
            return array();
        $values = $request->getParam($this->getFilter()->getRequestVar());
        if (!$values)
            $values = array();
        elseif (!is_array($values))
            $values = array($values);
        return $values;
    }

    public function getIsActive(){
        $values = $this->getFilterRequestValues();
        return !empty($values) && in_array($this->getValue(), $values);
    }

    protected function isInverseFilter(){
        return ($this->getFilter() instanceof RonisBT_CatalogNavigation_Model_Layer_Filter_Attribute) && $this->getFilter()->isInverseFilter();
    }

    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl(){
        if (Mage::helper('catalognavigation')->isExclusiveFilters()){
            $values = array();
        } else {
            $values = $this->getFilterRequestValues();
        }

        if (!$this->isInverseFilter() && !empty($values)){
            if (!in_array($this->getValue(), $values)){
                $values[] = $this->getValue();
            } else {
                return $this->getRemoveUrl();
            }
            $query = array(
                $this->getFilter()->getRequestVar() => $values,
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
            );
            return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
        }
        return parent::getUrl();
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl(){
        $values = $this->getFilterRequestValues();
        if (!empty($values)){
            if (in_array(is_array($this->getValue()) ? implode(',',$this->getValue()) : $this->getValue(),$values)){
                foreach($values as $key=>$value){
                    if ($value == (is_array($this->getValue()) ? implode(',',$this->getValue()) : $this->getValue()))
                        unset($values[$key]);
                }
            }
            $query = array(
                $this->getFilter()->getRequestVar() => count($values)?$values:$this->getFilter()->getResetValue()
            );
        } else {
            $query = array(
                $this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue()
            );
        }

        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        if (Mage::helper('catalognavigation')->isStandardLayout())
            $params['_escape']      = true;
        return Mage::getUrl('*/*/*', $params);
    }

}
