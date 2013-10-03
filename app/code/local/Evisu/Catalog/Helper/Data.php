<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 */

class Evisu_Catalog_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getPreviousProduct(Mage_Catalog_Model_Product $product)
    {
        $previousProduct = null;
        if($productsPositions = $this->getCurrentCategoryProductsPosition($product))
        {
            $keys = array_flip(array_keys($productsPositions));
            $values = array_keys($productsPositions);

            $previousProductId = null;
            //if current product is not first
            if(isset($values[$keys[$product->getId()] - 1]))
            {
                $previousProductId = $values[$keys[$product->getId()] - 1];
            }
            if($previousProductId)
            {
                $previousProduct = Mage::getModel('catalog/product')->load($previousProductId);
            }
        }
        return $previousProduct;
    }

    public function getNextProduct(Mage_Catalog_Model_Product $product)
    {

        $nextProduct = null;
        if($productsPositions = $this->getCurrentCategoryProductsPosition($product))
        {

            $keys = array_flip(array_keys($productsPositions));
            $values = array_keys($productsPositions);

            $nextProductId = null;
            //if current product is not last
            if(isset($values[$keys[$product->getId()] + 1]))
            {
                $nextProductId = $values[$keys[$product->getId()] + 1];
            }
            if($nextProductId)
            {
                $nextProduct = Mage::getModel('catalog/product')->load($nextProductId);
            }
        }
        return $nextProduct;
    }

    private function getCurrentCategoryProductsPosition(Mage_Catalog_Model_Product $product)
    {
        if(!$category = Mage::registry('current_category'))
        {
            $category = $product->getCategoryCollection()->addFilter('level', 3)->getFirstItem();
        }
        if($category)
        {
            /* @var $category Mage_Catalog_Model_Category */
            return $category->getProductsPosition();
        }
        return null;
    }
}
