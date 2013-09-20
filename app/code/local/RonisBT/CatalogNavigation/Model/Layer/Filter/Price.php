<?php

class RonisBT_CatalogNavigation_Model_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price
{
    public function getPriceRange()
    {
        $range = $this->getData('price_range');
        if (is_null($range)){
            $maxPrice = $this->getMaxPriceInt();
            $index = 1;
            do {
                $range = pow(10, (strlen(floor($maxPrice))-$index));
                $items = $this->getRangeItemCounts($range,true);
                $index++;
            }
            while($range>self::MIN_RANGE_POWER && count($items)<3);

            // RonisBT: limit price filter range
            $max_range = Mage::helper('catalognavigation')->getMaxPriceFilterRange();
            if ($max_range > 0){
                $range = min($max_range, $range);
            }

            $this->setData('price_range', $range);
        }
        return $range;
    }

    /**
     * Get maximum price from layer products set
     *
     * @return float
     */
    public function getMinPriceInt()
    {
        $minPrice = $this->getData('min_price_int');
        if (is_null($minPrice)) {
            $minPrice = $this->getLayer()->getEmptyProductCollection()->getMinPrice();
            $minPrice = floor($minPrice);
            $this->setData('min_price_int', $minPrice);
        }

        return $minPrice;
    }
    /**
     * Get maximum price from layer products set
     *
     * @return float
     */
    public function getMaxPriceInt()
    {
        $maxPrice = $this->getData('max_price_int');
        if (is_null($maxPrice)) {
            $maxPrice = $this->getLayer()->getEmptyProductCollection()->getMaxPrice();
            $maxPrice = ceil($maxPrice);
            $this->setData('max_price_int', $maxPrice);
        }

        return $maxPrice;
    }

    /**
     * Get information about products count in range
     *
     * @param   int $range
     * @return  int
     */
    public function getRangeItemCounts($range,$empty=false)
    {
        $rangeKey = 'range_item_counts_' . $range.'_'.($empty?'1':'0');
        $items = $this->getData($rangeKey);
        if (is_null($items)) {
            $items = $this->_getResource()->getCount($this, $range,$empty);
            // checking max number of intervals
            $i = 0;
            $lastIndex = null;
            $maxIntervalsNumber = $this->getMaxIntervalsNumber();
            $calculation = Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION);
            foreach ($items as $k => $v) {
                ++$i;
                if ($calculation == self::RANGE_CALCULATION_MANUAL && $i > 1 && $i > $maxIntervalsNumber) {
                    $items[$lastIndex] += $v;
                    unset($items[$k]);
                } else {
                    $lastIndex = $k;
                }
            }
            $this->setData($rangeKey, $items);
        }

        return $items;
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
     * Get data for build price filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        if (Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION) == self::RANGE_CALCULATION_IMPROVED) {
            return $this->_getCalculatedItemsData();
        } elseif ($this->getInterval()) {
            return array();
        }

        $range      = $this->getPriceRange();
        $dbRanges   = $this->getRangeItemCounts($range);
        $data       = array();

        if (!empty($dbRanges)) {
            $lastIndex = array_keys($dbRanges);
            $lastIndex = $lastIndex[count($lastIndex) - 1];

            $standard = Mage::helper('catalognavigation')->isStandardLayout();

            foreach ($dbRanges as $index => $count) {
                $fromPrice = (($index - 1) * $range);
                $toPrice = ($index * $range);
                $value = $fromPrice . '-' . $toPrice;
                if (!$standard || !$this->_checkOptionActive($value)){
                    $data[] = array(
                        'label' => $this->_renderRangeLabel($fromPrice, $toPrice),
                        'value' => $fromPrice . '-' . $toPrice,
                        'count' => $count,
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Apply price range filter to collection
     *
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock){
        /**
         * Filter must be string: $index,$range
         */
        $filters = $request->getParam($this->getRequestVar());
        /*if (!$filters){
            return $this;
        }*/

        $this->setRequest($request);
        $applyFilterToCollection = false;
        if (!is_array($filters))
            $filters = array($filters);

        foreach ($filters as $filter){
            $filterParams = explode(',', $filter);
            $filter = $this->_validateFilter($filterParams[0]);
            if (!$filter) {
                return $this;
            }

            list($from, $to) = $filter;
            $intervals = $this->getIntervals();
            if (!is_array($intervals))
                $intervals = array();
            $intervals[] = array($from, $to);
            $this->setIntervals($intervals);

            $this->getLayer()->getState()->addFilter($this->_createItem(
                $this->_renderRangeLabel(empty($from) ? 0 : $from, $to),
                $filter
            ));
            $applyFilterToCollection = true;

        }
        if ($applyFilterToCollection)
            $this->_applyPriceRange();

        Mage::helper('catalognavigation')->applyEmptyStatus($this, $filterBlock);
        return $this;
    }
}
