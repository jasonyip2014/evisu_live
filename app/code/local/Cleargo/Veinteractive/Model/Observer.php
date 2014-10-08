<?php
/**
 * Created by PhpStorm.
 * User: Jason Yip
 * Date: 9/17/14
 * Time: 6:30 PM
 */

class Cleargo_Veinteractive_Model_Observer {
    const HEADER_ID = "unique_id";
    const HEADER_CATE_ONE = "category1";
    const HEADER_CATE_TWO = "category2";
    const HEADER_BRAND = "brand";
    const HEADER_NAME = "product_name";
    const HEADER_DESCRIPTION = "description";
    const HEADER_LONG_DESCRIPTION = "long_description";
    const HEADER_PRICE = "price";
    const HEADER_LINK = "product_link";
    const HEADER_IMAGE = "image";
    const BRAND = 'EVISU';

    public function getDataFeed() {
        $dir_path = Mage::getBaseDir('media').DS."datafeed";
        if(!file_exists($dir_path)) {
            if(!mkdir($dir_path, 0755)) {
                Mage::log("cannot create the directory in ".$dir_path);
                die("Error: cannot create the directory in ".$dir_path);
            }
        }

        $_websites = Mage::app()->getWebsites();
        Foreach ($_websites as $website) {
            $fileName = $website->getCode().".csv";
            $file_path = $dir_path.DS.$fileName; // path of the file
            $mage_csv = new Varien_File_Csv();
            $datafeed = array();
            //Header
            $_data = array();
            $_data['sku'] = Cleargo_Veinteractive_Model_Observer::HEADER_ID;
            $_data['first_category'] = Cleargo_Veinteractive_Model_Observer::HEADER_CATE_ONE;
            $_data['second_category'] = Cleargo_Veinteractive_Model_Observer::HEADER_CATE_TWO;
            $_data['brand'] = Cleargo_Veinteractive_Model_Observer::HEADER_BRAND;
            $_data['name'] = Cleargo_Veinteractive_Model_Observer::HEADER_NAME;
            $_data['description'] = Cleargo_Veinteractive_Model_Observer::HEADER_DESCRIPTION;
            $_data['long_description'] = Cleargo_Veinteractive_Model_Observer::HEADER_LONG_DESCRIPTION;
            $_data['price'] = Cleargo_Veinteractive_Model_Observer::HEADER_PRICE;
            $_data['link'] = Cleargo_Veinteractive_Model_Observer::HEADER_LINK;
            $_data['image'] = Cleargo_Veinteractive_Model_Observer::HEADER_IMAGE;
            $datafeed[] = $_data;

            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $baseCurrencyCode = $store->getBaseCurrencyCode();
                    $currentCurrencyCode = $store->getCurrentCurrencyCode();
                    $productCollection = Mage::getModel('catalog/product')->getCollection();
                    $productCollection->addStoreFilter($store->getId());
                    $productCollection->addAttributeToSelect(array("sku","second_name","description","short_description","price","image"));
                    $productCollection->addAttributeToFilter("visibility", array("neq"=>1));
                    $productCollection->addAttributeToFilter("status", 1);

                    foreach ($productCollection as $prod) {
                        $product = Mage::getModel('catalog/product')->setStoreId($store->getId())->load($prod->getId());
                        $categoryIds = $product->getCategoryIds();
                        switch(count($categoryIds)) {
                            case 1:
                                $firstCategoryId = $categoryIds[0];
                                $firstCategoryName = Mage::getModel('catalog/category')->load($firstCategoryId)->getName();
                                $secondCategoryId = null;
                                $secondCategoryName = null;
                                break;
                            case 2:
                                $firstCategoryId = $categoryIds[0];
                                $firstCategoryName = Mage::getModel('catalog/category')->load($firstCategoryId)->getName();
                                $secondCategoryId = $categoryIds[1];
                                $secondCategoryName = Mage::getModel('catalog/category')->load($secondCategoryId)->getName();
                                break;
                            default:
                                $firstCategoryId = null;
                                $firstCategoryName = null;
                                $secondCategoryId = null;
                                $secondCategoryName = null;
                        }
                        $_data = array();
                        $_data['sku'] = $product->getSku();
                        $_data['first_category'] = $firstCategoryName;
                        $_data['second_category'] = $secondCategoryName;
                        $_data['brand'] = Cleargo_Veinteractive_Model_Observer::BRAND;
                        $_data['name'] = $product->getSecondName();
                        $_data['description'] = $product->getShortDescription();
                        $_data['long_description'] = $product->getDescription();
                        $_data['price'] = $product->getPrice();
                        //$_data['price'] = Mage::helper('directory')->currencyConvert($product->getPrice(), $currentCurrencyCode, $baseCurrencyCode);
                        $_data['link'] = $product->getProductUrl();
                        $_data['image'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                        $datafeed[] = $_data;
                    }
                }
            }
            try {
                $mage_csv->saveData($file_path, $datafeed);
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
    }

    public function test() {
        Mage::log("WORKS!", null, "test20140925.log");
        return true;
    }
}