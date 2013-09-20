<?php
class RonisBT_CatalogNavigation_Block_Search_Layer extends Mage_CatalogSearch_Block_Layer
{
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