<?php
class Evisu_FitGuide_Model_Config_Source_Fitattribute extends RonisBT_Cms_Model_Entity_Attribute_Source_Options
{
    public function __construct(){
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'fit_guide');
        if ($attribute->usesSource()) {
            //var_dump(get_class($attribute));
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            foreach($attribute->getSource()->getAllOptions(false) as $option)
            $this->_optionList[$option['value']] = $option['label'];
        }
    }
}
