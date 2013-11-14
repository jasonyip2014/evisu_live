<?php
/*
 * Crombie_Lookbook_Helper_Data
 */

class Evisu_LookBook_Model_Product extends Mage_Core_Model_Abstract
{
    private $products = null;

    public function setProducts(array $productSku)
    {

        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*') //Mage::getSingleton('catalog/config')->getProductAttributes()
            ->addAttributeToFilter('sku', array('in' => $productSku))
        ;

        /* @var $product Mage_Catalog_Model_Product */
        $product = $productCollection->getFirstItem();


        $assProducts = $this->getAssociatedProducts($product);

        if(count($assProducts) > 0)
        {
            $this->products[$product->getSku()] = array(
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'url' => $product->getProductUrl(),
                'wishlistUrl' => Mage::helper('wishlist')->getAddUrl($product),
            );
            foreach($assProducts as $assProduct)
            {
                $this->products[$product->getSku()]['associated_products'][$assProduct->getId()] = array(
                    'id' => $assProduct->getId(),
                    'name' => $assProduct->getName(),
                    'second_name' => (($assProduct->getSecondName())?$assProduct->getSecondName():''),
                    'price_old' => Mage::helper('core')->currency(Mage::helper('tax')->getPrice($assProduct, $assProduct->getPrice(), true), true, false),
                    'price' => Mage::helper('core')->currency(Mage::helper('tax')->getPrice($assProduct, $assProduct->getFinalPrice(), true), true, false),
                    'url' =>$assProduct->getProductUrl(),
                    'image' => (string) Mage::helper('catalog/image')->init($assProduct, 'small_image')->Resize(250, 520),
                );
            }
        }
        return $this;
    }

    public function getProductBySKU($productSku)
    {
        $product = null;
        if($this->products && isset($this->products[$productSku]))
        {
            return $this->products[$productSku];
        }
        return $product;
    }

    private function getAssociatedProducts(Mage_Catalog_Model_Product $product)
    {
        if($product->getTypeId() === 'grouped')
        {
            return $product->getTypeInstance(true)
                ->getAssociatedProducts($product);
        }
        return null;
    }
}