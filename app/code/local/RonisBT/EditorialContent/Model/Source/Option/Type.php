<?php

class RonisBT_EditorialContent_Model_Source_Option_Type
{
    const ADVANCED_CMS_PAGE_TYPE = 'pec_container';

    public function toOptionArray()
    {
        $items = array();
        $items[] =  array(
            'label' => 'A non-existent type (will be removed when saving)',
            'value' => 0
        );

        $pecContainer = Mage::getResourceModel('cmsadvanced/page_collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('page_type', self::ADVANCED_CMS_PAGE_TYPE)
            ->getFirstItem();
        /* @var $pecContainer RonisBT_Cms_Model_Page */
        if($pecContainer)
        {
            $pecItems = $pecContainer->getChildren();
            foreach($pecItems as $pec_item)
            {
                $items[] = array(
                    'label' => $pec_item->getName() . ' - [ '. $pec_item->getPageType()->getName() .' ]',
                    'value' => $pec_item->getEntityId()
                );
            }
        }

        return $items;
    }
}
