<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$origFile = fopen('products.csv', 'r');

$header = fgetcsv($origFile);

$sizes = array();
$styles = array();
$materials = array();
$colours = array();

$productGroups = array();

$images = array();

$skus = array();

while (($row = fgetcsv($origFile)) !== false) {
    $product = array_combine($header, $row);

    $handle = $product['Handle'];
    
    if ($product['Published'] === 'false') {
        $productGroups[$handle] = false;
        continue;
    }

    if (isset($productGroups[$handle]) && $productGroups[$handle] === false) {
        continue;
    }

    $size = $product['Option1 Value'];
    $sizes[$size] = 1;

    $material = $product['Option2 Name'] === 'Material' ? $product['Option2 Value'] : null;
    $materials[$material] = 1;

    $colour = $product['Option2 Name'] === 'Color' ? $product['Option2 Value'] : null;
    $colours[$colour] = 1;

    $type = $product['Type'];
    $types[$type] = 1;

    $imageSrc = $product['Image Src'];

    $image = null;

    if ($imageSrc) {
        $image = '/shopify/' . $image . basename(parse_url($imageSrc, PHP_URL_PATH));
        $images[$image] = $imageSrc;
    }

    $sku = $product['Variant SKU'];

    if (isset($skus[$sku]) || $sku === 'S11MD2008.12BLUE') {
        continue;
    }

    $skus[$sku] = $sku;

    $productGroups[$handle][] = array(
        'url_key' => $product['Handle'],
        'name' => $product['Title'],
        'description' => $product['Body (HTML)'],
        //Vendor
        //'type' => $type,
        //Tags
        'status' => $product['Published'] === 'false' ? 2 : 1,
        'sku' => $sku,
        'size' => $size,
        'material' => $material,
        //'colour' => $colour,
        //Option2 Name
        'hs_code' => $product['Option3 Value'],
        'weight' => $product['Variant Grams'],
        //Variant Inventory Tracker
        'qty' => $product['Variant Inventory Qty'],
        //Variant Inventory Policy
        //Variant Fulfillment Service
        'price' => $product['Variant Price'],
        //Variant Compare At Price
        //Variant Requires Shipping
        //Variant Taxable
        'tax_class_id' => 2,//taxable
        //Variant Barcode
        'image' => $image,
    );
}
/*print_r($sizes);
print_r($types);
print_r($materials);
print_r($colours);
die;*/
upload_images($images);

$resultRows = array();
$resultFields = array(
    'sku',
    '_store',
    '_attribute_set',
    '_type',
    '_product_websites',
    'description',
    'image',
    'small_image',
    'manufacturer',
    'material',
    'name',
    'price',
    'size',
    'status',
    'hs_code',
    'tax_class_id',
    'thumbnail',
    'url_key',
    'visibility',
    'weight',
    'qty',
    'is_in_stock',
    '_media_attribute_id',
    '_media_image',
    '_media_lable',
    '_media_position',
    '_media_is_disabled',
    '_super_products_sku',
    '_super_attribute_code',
    '_super_attribute_option',
);

$resultFields = array_fill_keys($resultFields, null);

foreach ($productGroups as $group => $products) {
    if ($products === false) {
        continue;
    }

    $configurableProductRows = array();
    $simpleProducts = array();

    $name = null;
    $description = null;

    $imagePos = 0;

    foreach ($products as $product) {
        if (!$product['sku']) {
            continue;
        }

        if (!$name) {
            $name = $product['name'];
        }

        if (!$description) {
            $description = $product['description'] ? $product['description'] : '&nbsp;';
        }

        $configurableProductRow = array(
            '_super_attribute_code' => 'size',
            '_super_attribute_option' => $product['size'],
            '_super_products_sku' => $product['sku'],
        );

        $image = $product['image'];
        $imagePos ++;

        if ($image) {
            $configurableProductRow = array_merge($configurableProductRow, array(
                '_media_attribute_id' => 88,
                '_media_image' => $image,
                '_media_position' => $imagePos,
                '_media_is_disabled' => 0,
            ));
        }

        $common = array(
            'name' => $name,
            'description' => $description,
            'price' => $product['price'],
            'tax_class_id' => $product['tax_class_id'],
            'weight' => $product['weight'],
            '_attribute_set' => 'Default',
            '_product_websites' => 'base',
            'status' => $product['status'],
        );

        if (empty($configurableProductRows)) {
            $configurableProductRow = array_merge($configurableProductRow, $common, array(
                'image' => $image,
                'small_image' => $image,
                'thumbnail' => $image,
                'sku' => preg_replace('/-+\w+$/', '', $product['sku']),
                'url_key' => $product['url_key'],
                '_type' => 'configurable',
                'visibility' => 4,
                'is_in_stock' => 1,
                //'type' => $product['type'],
                'material' => $product['material'],
            ));
        }

        $configurableProductRows[] = $configurableProductRow;

        $simpleProducts[] = array_merge($common, array(
            'sku' => $product['sku'],
            'url_key' => $product['url_key'] . '-' . uniqid(),
            'size' => $product['size'],
            'qty' => $product['qty'],
            '_type' => 'simple',
            'visibility' => 1,
            'is_in_stock' => $product['qty'] > 0 ? 1 : 0,
        ));
    }

    if (!$configurableProductRows || !$simpleProducts) {
        continue;
    }

    $rows = array_merge($simpleProducts, $configurableProductRows);

    foreach ($rows as $row) {
        $resultRows[] = array_merge($resultFields, $row);
    }
}

$resultFile = fopen('mage_product.csv', 'w+');

$header = null;
foreach ($resultRows as $row) {
    if (!$header) {
        $header = array_keys($row);
        fputcsv($resultFile, $header);
    }
    fputcsv($resultFile, $row);
}

function upload_images($images) {
    $baseDestPath = dirname(__FILE__) . '/../media/import/';

    foreach ($images as $destName => $src) {
        $destPath = $baseDestPath . $destName;

        if (!file_exists($destPath)) {
            $image = file_get_contents($src);
            file_put_contents($baseDestPath . $destName, $image);
        }
    }
}