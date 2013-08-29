<?php
class RonisBT_Cms_Model_Entity_Attribute_Backend_Serialized_Array_Sorted extends RonisBT_Cms_Model_Entity_Attribute_Backend_Serialized_Array
{
    protected function _prepareValue($value)
    {
        if (is_array($value)) {
            usort($value, array($this, 'sortByPosition'));
        }
        return $value;
    }

    public function sortByPosition($a, $b)
    {
        $aPos = isset($a['position']) ? (int) $a['position'] : 0;
        $bPos = isset($b['position']) ? (int) $b['position'] : 0;

        if ($aPos === $bPos) {
            return 0;
        }

        return ($aPos < $bPos) ? -1 : 1;
    }
}