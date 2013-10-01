<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 9/24/13
 * Time: 4:42 PM
 * To change this template use File | Settings | File Templates.
 */
class RonisBT_EditorialContent_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getProductEditorialContent(Mage_Catalog_Model_Product $_product)
    {

        if(!$pec_options = $_product->getPecOptions())
        {
           return null;
        }
        $pec_options = unserialize($pec_options);

        $pecIds = array();
        foreach($pec_options as $pec_option)
        {
            $pecIds[] = $pec_option['pec_id'];
        }
        $pec_items = Mage::getResourceModel('cmsadvanced/page_collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('entity_id', array("in" => $pecIds));
        /* @var $pec_items RonisBT_Cms_Model_Resource_Eav_Mysql4_Page_Collection */

        for($i = 0; $i < count($pec_options); $i++)
        {
            $pec_options[$i]['block'] = $pec_items->getItemById($pec_options[$i]['pec_id']);
        }

        return $pec_options;
    }
}