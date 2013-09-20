<?php

class RonisBT_CatalogNavigation_Model_Resource_Eav_Mysql4_Layer_Filter_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
{
    protected $select_for_count = null;

    /**
     * Retrieve array with products counts per price range
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $range
     * @return array
     */
    public function getCount($filter, $range, $empty=false)
    {
        if ($empty)
        {
            $select     = $this->_getSelect($filter,$filter->getLayer()->getEmptyProductCollectionSelect());
        }
        elseif (!is_null($this->select_for_count))
        {
            $select     = $this->select_for_count;
            // reset columns, order and limitation conditions
            $select->reset(Zend_Db_Select::COLUMNS);
            $select->reset(Zend_Db_Select::ORDER);
            $select->reset(Zend_Db_Select::LIMIT_COUNT);
            $select->reset(Zend_Db_Select::LIMIT_OFFSET);

            $select     = $this->_getSelect($filter,$select);
        }
        else
        {
            $collection = $filter->getLayer()->getProductCollection();
            $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

            $select = clone $collection->getSelect();
            $select     = $this->_getSelect($filter,$select);
        }
        $priceExpression = $this->_getFullPriceExpression($filter, $select);

        /**
         * Check and set correct variable values to prevent SQL-injections
         */
        $range = floatval($range);
        if ($range == 0) {
            $range = 1;
        }
        $countExpr = new Zend_Db_Expr('COUNT(distinct e.entity_id)');
        $rangeExpr = new Zend_Db_Expr("FLOOR(({$priceExpression}) / {$range}) + 1");

        $select->columns(array(
            'range' => $rangeExpr,
            'count' => $countExpr
        ));
        $select->group($rangeExpr)->order("$rangeExpr ASC");

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * Retrieve clean select with joined price index table
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter,$select=null)
    {
        if (is_null($select))
        {
            $collection = $filter->getLayer()->getProductCollection();
            $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

            if (!is_null($collection->getCatalogPreparedSelect())) {
                $select = clone $collection->getCatalogPreparedSelect();
            } else {
                $select = clone $collection->getSelect();
            }
        }
        else
        {
            $select = clone $select;
        }

        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        // remove join with main table
        $fromPart = $select->getPart(Zend_Db_Select::FROM);
        if (!isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS])
            || !isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS])
        ) {
            return $select;
        }

        // processing FROM part
        $priceIndexJoinPart = $fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS];
        $priceIndexJoinConditions = explode('AND', $priceIndexJoinPart['joinCondition']);
        $priceIndexJoinPart['joinType'] = Zend_Db_Select::FROM;
        $priceIndexJoinPart['joinCondition'] = null;
        $fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS] = $priceIndexJoinPart;
        unset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS]);
        $select->setPart(Zend_Db_Select::FROM, $fromPart);
        foreach ($fromPart as $key => $fromJoinItem) {
            $fromPart[$key]['joinCondition'] = $this->_replaceTableAlias($fromJoinItem['joinCondition']);
        }
        $select->setPart(Zend_Db_Select::FROM, $fromPart);

        // processing WHERE part
        $wherePart = $select->getPart(Zend_Db_Select::WHERE);
        foreach ($wherePart as $key => $wherePartItem) {
            $wherePart[$key] = $this->_replaceTableAlias($wherePartItem);
        }
        $select->setPart(Zend_Db_Select::WHERE, $wherePart);
        $excludeJoinPart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.entity_id';
        foreach ($priceIndexJoinConditions as $condition) {
            if (strpos($condition, $excludeJoinPart) !== false) {
                continue;
            }
            $select->where($this->_replaceTableAlias($condition));
        }
        $select->where($this->_getPriceExpression($filter, $select) . ' IS NOT NULL');

        return $select;
    }

    public function applyPriceRange($filter)
    {
        $intervals = $filter->getIntervals();
        if (!$intervals && !is_array($intervals)) {
            return $this;
        }

        $select = $filter->getLayer()->getProductCollection()->getSelect();
        $this->select_for_count = clone $select;
        $priceExpr = $this->_getPriceExpression($filter, $select, false);

        $connection = $this->_getReadAdapter();

        foreach($intervals as $interval)
        {
            list($from, $to) = $interval;
            if ($from === '' && $to === '') {
                return $this;
            }
            if ($to !== '') {
                $to = (float)$to;
                if ($from == $to) {
                    $to += self::MIN_POSSIBLE_PRICE;
                }
            }
            $condition= array();

            if ($from !== '') {
                $condition[] = $connection->quoteInto($priceExpr . ' >= ?' , $this->_getComparingValue($from, $filter));
            }
            if ($to !== '') {
                $condition[] = $connection->quoteInto($priceExpr . ' < ?' , $this->_getComparingValue($to, $filter));
            }
            if (count($condition)!=0)
                $conditions[] = $condition;
        }

        foreach($conditions as $key => $condition)
            $conditions[$key] = '('.join(' AND ', $condition).')';

        $select
            ->where(join(' OR ', $conditions));

        if (is_array($filter->getLayer()->getSelectsForCount()) && ($selects = $filter->getLayer()->getSelectsForCount()))
            foreach($selects as $key => $select)
                $selects[$key]
                    ->where(join(' OR ', $conditions));

        if (is_array($filter->getLayer()->getSelectsForCount()))
            $selects[$filter->getAttributeModel()->getAttributeCode()] = &$this->select_for_count;
        else
            $selects = array($filter->getAttributeModel()->getAttributeCode()=>&$this->select_for_count);

        $filter->getLayer()->setSelectsForCount($selects);

        return $this;

    }
}
