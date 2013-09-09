<?php

class RonisBT_TopMenu_Model_Source_Item_Type extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_options;

    public function toOptionArray(){
        if (is_null($this->_options)){
            $this->_options = array(
                //array('value' => '', 'label' => ''),
                array('value' => 'block', 'label' => 'Block'),
                array('value' => 'category', 'label' => 'Category'),
                array('value' => 'menu', 'label' => 'Menu item'),
            );
        }
        return $this->_options;
    }

    public function getAllOptions(){
        return $this->toOptionArray();
    }

}
