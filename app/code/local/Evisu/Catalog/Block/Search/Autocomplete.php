<?php
/**
 * Autocomplete queries list
 */
class Evisu_Catalog_Block_Search_Autocomplete extends Mage_Core_Block_Abstract
{
    protected $_suggestData = null;

    protected function _toHtml()
    {
        $html = 'no-results';

        if (!$this->_beforeToHtml()) {
            return $html;
        }

        $suggestData = $this->getSuggestData();
        if (!($count = count($suggestData))) {
            return $html;
        }

        $count;

        $html = '<ul><li style="display:none"></li>';
        foreach ($suggestData as $index => $item) {
            if ($index == 0) {
                $item['row_class'] .= ' first';
            }

            if ($index == $count - 1) {
                $item['row_class'] .= ' last';
            }

            $html .=  '<li title="'.$this->escapeHtml($item['title']).'" class="'.$item['row_class'].'">'
                . '<span class="amount">'.$item['num_of_results'].'</span>'.$this->escapeHtml($item['title']).'</li>';
        }

        $html.= '</ul>';

        return $html;
    }

    public function getSuggestData()
    {
        if (!$this->_suggestData) {
            $collection = $this->helper('catalogsearch')->getSuggestCollection();
            $query = $this->helper('catalogsearch')->getQueryText();
            $counter = 0;
            $data = array();
            foreach ($collection as $item) {
                //var_dump($item->getData());
                $_data = array(
                    'title' => $item->getQueryText(),
                    'row_class' => (++$counter)%2?'odd':'even',
                    'num_of_results' => $item->getNumResults()
                );

                if ($item->getQueryText() == $query) {
                    array_unshift($data, $_data);
                }
                else {
                    $data[] = $_data;
                }
            }
            $this->_suggestData = $data;
        }
        return $this->_suggestData;
    }
}
