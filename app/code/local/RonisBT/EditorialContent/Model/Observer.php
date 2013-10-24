<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Editorial Content Products Observer
 */
class RonisBT_EditorialContent_Model_Observer
{

    private function cmp($a, $b)
    {
        return strnatcmp($a["position"], $b["position"]);
    }

    /**
     * @param Varien_Object $observer
     * @return Mage_Bundle_Model_Observer
     */
    public function prepareProductSave($observer)
    {

        $request = $observer->getEvent()->getRequest();
        $product = $observer->getEvent()->getProduct();

        if (($pec_array = $request->getPost('pec_options')))
        {
            //deleting non-existent types
            $pec_array_clear = array();
            foreach($pec_array as $pec_option)
            {
                if($pec_option['pec_id'] != 0)
                {
                    $pec_array_clear[] = $pec_option;
                }
            }

            if(count($pec_array_clear) > 0)
            {
                //sorting by position
                usort($pec_array_clear, array($this,"cmp"));
                $pec_options = serialize($pec_array_clear);
                $product->setPecOptions($pec_options);
            }
            else
            {
                $product->setPecOptions(null);
            }
        }
        else
        {
            $product->setPecOptions(null);
        }
        return $this;
    }

    /**
     * Setting attribute tab block
     *
     * @param Varien_Object $observer
     * @return Mage_Bundle_Model_Observer
     */
    public function setAttributeTabBlock($observer)
    {
        Mage::helper('adminhtml/catalog')->setAttributeTabBlock('ronisbt_editorialcontent/adminhtml_catalog_product_edit_tab_attributes');
        return $this;
    }
}
