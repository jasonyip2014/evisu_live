<?php
class RonisBT_Cms_Block_Adminhtml_System_Config_Form_Field_Gridfield extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_directions;
    protected $_styles;

    public function __construct()
    {
        $this->setTemplate('cmsadvanced/system/config/form/field/array.phtml');
        return parent::__construct();
    }

    public function getArrayRows()
    {
        $rows = parent::getArrayRows();

        foreach ($rows as $row) {
            foreach ($row->getData() as $key => $value) {
                if (!isset($this->_columns[$key]))
                    continue;

                $column = $this->_columns[$key];

                if ($column['renderer'] instanceof Mage_Core_Block_Html_Select) {
                    $row->setData(sprintf('option_extra_attr_%s',  $column['renderer']->calcOptionHash($value)), 'selected="selected"');
                }
            }
        }
        return $rows;
    }

}