<?php
abstract class RonisBT_Cms_Block_Adminhtml_Helper_Form_Gridfield extends Varien_Data_Form_Element_Abstract
{
    protected $_fieldName = 'cmsadvanced/adminhtml_system_config_form_field_gridfield';

    public function getElementHtml()
    {
        if (!$this->_fieldName) {
            return;
        }

        $grid = Mage::app()->getLayout()->createBlock($this->_fieldName);
        $grid->setElement($this);

        $this->_prepareColumns($grid);

        return $grid->toHtml();
    }

    abstract protected function _prepareColumns($grid);
}