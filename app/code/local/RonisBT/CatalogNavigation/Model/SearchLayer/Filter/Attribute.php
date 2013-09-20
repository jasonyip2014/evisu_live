<?php
class RonisBT_CatalogNavigation_Model_SearchLayer_Filter_Attribute extends RonisBT_CatalogNavigation_Model_Layer_Filter_Attribute
{
    /**
     * Check whether specified attribute can be used in LN
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute  $attribute
     * @return bool
     */
    protected function _getIsFilterableAttribute($attribute)
    {
        return $attribute->getIsFilterableInSearch();
    }

}
