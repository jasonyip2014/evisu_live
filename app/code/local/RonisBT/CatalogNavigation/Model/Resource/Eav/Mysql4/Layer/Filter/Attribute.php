<?php
class RonisBT_CatalogNavigation_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
{

    protected $select_for_count = null;

    public function storeFilterState($collection){
        $this->select_for_count = clone $collection->getSelect();
        return $this;
    }

    /**
     * Apply attribute filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @param int $value
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
     */
    public function applyFilterToCollection($filter, $values)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $this->storeFilterState($collection);
        $attribute  = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        if (!is_array($values)) {
            $values = array($values);
        }
        $conditions = array();
        foreach($values as $value)
        $conditions[] = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            $connection->quoteInto("{$tableAlias}.value = ?", $value)
        );
        foreach($conditions as $key => $condition)
            $conditions[$key] = '('.join(' AND ', $condition).')';
        $collection->getSelect()->join(
            array($tableAlias => $this->getMainTable()),
            join(' OR ', $conditions),
            array()
        );
        $collection->getSelect()->distinct(true);

        if (is_array($filter->getLayer()->getSelectsForCount()) && ($selects = $filter->getLayer()->getSelectsForCount()))
            foreach($selects as $key => $select)
            {
                if ($selects[$key]==null)
                    echo $key;
                $selects[$key]->join(
                    array($tableAlias => $this->getMainTable()),
                    join(' OR ', $conditions),
                    array()
                )->distinct(true);
            }

        if (is_array($filter->getLayer()->getSelectsForCount()))
            $selects[$filter->getAttributeModel()->getAttributeCode()] = &$this->select_for_count;
        else
            $selects = array($filter->getAttributeModel()->getAttributeCode()=>&$this->select_for_count);

        $filter->getLayer()->setSelectsForCount($selects);

        return $this;
    }

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @return array
     */
    public function getCount($filter)
    {
        if ($this->select_for_count)
            $select = $this->select_for_count;
        else
            // clone select from collection with filters
            $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute  = $filter->getAttributeModel();
        $tableAlias = $attribute->getAttributeCode() . '_count_idx';
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );

        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array('value', 'count' => "COUNT(distinct {$tableAlias}.entity_id)"))
            ->group("{$tableAlias}.value")
            ->distinct();

        return $connection->fetchPairs($select);
    }

}
