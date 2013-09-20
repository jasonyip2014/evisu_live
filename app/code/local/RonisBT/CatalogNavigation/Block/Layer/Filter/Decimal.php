<?php

class RonisBT_CatalogNavigation_Block_Layer_Filter_Decimal extends Mage_Catalog_Block_Layer_Filter_Decimal
{

    public function getFilterType(){
        return 'decimal';
    }

    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }

}
