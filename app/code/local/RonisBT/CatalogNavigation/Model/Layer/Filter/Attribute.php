<?php
class RonisBT_CatalogNavigation_Model_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{

    const INVERSE_HIDE_VALUE = 'hide';

    protected $_is_inverse;
    protected $_collection_copy;

    public function isInverseFilter(){
        if (is_null($this->_is_inverse)){
            if (!Mage::registry('catalog_navigation_inverse_filters_disabled')){
                $this->_is_inverse = in_array($this->_requestVar, Mage::helper('catalognavigation')->getInverseFilterAttributes());
            } else {
                $this->_is_inverse = false;
            }
        }
        return $this->_is_inverse;
    }

    protected function _checkOptionActive($value){
        $request = $this->getRequest();
        if (!$request)
            return false;
        $values = $this->getRequest()->getParam($this->getRequestVar());
        if (!$values)
            return false;
        if (!is_array($values))
            $values = array($values);
        return in_array($value, $values);
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Varien_Object $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock){
        $filters = $request->getParam($this->_requestVar);
        /*if (is_array($filter)) {
            return $this;
        }*/
        $this->setRequest($request);
        $applyFilterToCollection = false;
        if (!is_array($filters))
            $filters = array($filters);

        $apply_inverse_filter = false;
        $active_filters = array();
        foreach($filters as $filter){
            if ($filter == self::INVERSE_HIDE_VALUE){
                if (Mage::registry('catalog_navigation_inverse_filters_active')
                    &&
                    $this->isInverseFilter()
                ){
                    $apply_inverse_filter = false;
                    $applyFilterToCollection = false;
                    continue;
                } else {
                    $apply_inverse_filter = true;
                    $applyFilterToCollection = false;
                    break;
                }
            }
            $text = $this->_getOptionText($filter);
            if ($filter && $text){
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
                //$this->_items = array();
                $applyFilterToCollection = true;
            }
            $active_filters[] = $filter;
        }
        $filters = $active_filters;

        if (!$applyFilterToCollection
            &&
                Mage::registry('catalog_navigation_inverse_filters_active')
            &&
                $this->isInverseFilter()
        ){
            // Activate all inverse filter options
            $options = $this->getAttributeModel()->getSource()->getAllOptions(false);
            foreach ($options as $option){
                $filters[] = $option['value'];
                $this->getLayer()->getState()->addFilter($this->_createItem($option['label'], $option['value']));
            }
            $applyFilterToCollection = true;
        }

        if ($applyFilterToCollection){
            $this->_getResource()->applyFilterToCollection($this, $filters);
        } elseif ($apply_inverse_filter /*$this->isInverseFilter()*/){
            $this->applyInverseValuesFilter();
        }

        Mage::helper('catalognavigation')->applyEmptyStatus($this, $filterBlock);
        return $this;
    }

    protected function applyInverseValuesFilter(){
        $collection = $this->getLayer()->getProductCollection();

        // clone select from collection with filters
        $this->_collection_copy = clone $collection->getSelect();
        $this->_getResource()->storeFilterState($collection);

        $attribute  = $this->getAttributeModel();
        $subselect = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
            ->from(array('attr'=>Mage::getSingleton('core/resource')->getTableName('catalogindex/eav')), 'attr.entity_id')
            ->where('attr.store_id = ?', Mage::app()->getStore()->getId())
            ->where('attr.attribute_id = ?', $attribute->getId());
        $collection->getSelect()->where('e.entity_id NOT IN (?)', $subselect);
        return $this;
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     */
    protected function _getItemsData(){
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $key = $this->getLayer()->getStateKey().'_'.$this->_requestVar;
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null){
            $options = $attribute->getFrontend()->getSelectOptions();
            $optionsCount = $this->_getResource()->getCount($this);
            $data = array();

            $items_count = 0;
            $standard = Mage::helper('catalognavigation')->isStandardLayout();
            foreach ($options as $option){
                if (is_array($option['value'])){
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])
                    && (!$standard || !$this->_checkOptionActive($option['value']))
                ) {
                    // Check filter type
                    if ($this->_getIsFilterableAttribute($attribute) == self::OPTIONS_ONLY_WITH_RESULTS){
                        if (!empty($optionsCount[$option['value']])){
                            $data[] = array(
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'count' => $optionsCount[$option['value']],
                            );
                            $items_count += $optionsCount[$option['value']];
                        }
                    } else {
                        $data[] = array(
                            'label' => $option['label'],
                            'value' => $option['value'],
                            'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                        );
                        $items_count += isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0;
                    }
                }
            }
            if ($this->isInverseFilter() && $items_count > 0){
                $label = ($this->_requestVar == 'sale') ?
                    Mage::helper('catalognavigation')->__('Hide sale items')
                    :
                    Mage::helper('catalognavigation')->__('Hide').' '.$attribute->getStoreLabel();
                // TODO: manage labels in admin
                $data[] = array(
                    'label' => $label,
                    'value' => self::INVERSE_HIDE_VALUE,
                    'count' => 1, //TODO: getInverseFilterCount
                );
            }

            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG.':'.$attribute->getId()
            );

            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }

        return $data;
    }
}
