<?php

class RonisBT_CatalogNavigation_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
    /**
     * Prepare child blocks
     *
     * @return Mage_Catalog_Block_Layer_View
     */
    protected function _prepareLayout()
    {
        $this->getLayer()->setEmptyProductCollectionSelect(clone $this->getLayer()->getProductCollection()->getSelect());
        Mage::dispatchEvent('layer_catalog_product_list_collection', array(
            'collection' => $this->getLayer()->getProductCollection()
        ));
        $this->getLayer()->getProductCollection()->getMaxPrice();
        $this->getLayer()->setEmptyProductCollection(clone $this->getLayer()->getProductCollection());
        Mage::dispatchEvent('catalog_empty_product_list_collection_select', array(
            'select' => $this->getLayer()->getEmptyProductCollectionSelect(),
            'collection' => $this->getLayer()->getEmptyProductCollection()
        ));

        return parent::_prepareLayout();
    }
}
