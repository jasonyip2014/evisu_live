<?php

class Evisu_Contacts_Block_Adminhtml_Advancedcms_Grid extends  RonisBT_Cms_Block_Adminhtml_Block_Grid
{
    const ENTITY_TYPE = 'contact_us_grid';
    const BLOCK_KEY = 'contact_us_grid';

    public function __construct()
    {
        parent::__construct(array('entity_type' => self::ENTITY_TYPE,'block_key' => self::BLOCK_KEY));
    }

    protected function _prepareCollection()
    {
        parent::_prepareCollection();

        $collection = $this->getModel()->getCollection()
                    ->setStore($this->_getStore())
                    ->addAttributeToSelect('*');
        Mage::log($this->_getStore()->getId(), null, 'store.log');
        //var_dump($this->_getStore()->getId());
        if($storeId = $this->_getStore()->getId())
        {
            $collection->addFieldToFilter('cu_store_id', $storeId);
        }

        $collection->addAttributeToFilter('page_id', $this->getPageId());

        $this->setCollection($collection);

        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    public function getCreateButtonHtml()
    {
        return '';
    }
}
