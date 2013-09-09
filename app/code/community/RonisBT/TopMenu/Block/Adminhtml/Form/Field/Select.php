<?php

class RonisBT_TopMenu_Block_Adminhtml_Form_Field_Select extends Mage_Core_Block_Html_Select
{
    public function setInputName($value){
        return $this->setName($value);
    }

    /* Code from enterprise magento */
    protected function _optionToHtml($option, $selected=false){
        $selectedHtml = $selected ? ' selected="selected"' : '';
        if ($this->getIsRenderToJsTemplate() === true){
            $selectedHtml .= ' #{option_extra_attr_' . self::calcOptionHash($option['value']) . '}';
        }
        $html = '<option value="'.$this->htmlEscape($option['value']).'"'.$selectedHtml.'>'.$this->htmlEscape($option['label']).'</option>';

        return $html;
    }

    public function calcOptionHash($optionValue){
        return sprintf('%u', crc32($this->getName() . $this->getId() . $optionValue));
    }

}
