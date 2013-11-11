<?php
class RonisBT_Cms_Model_Config_Source_Redirecttype extends RonisBT_Cms_Model_Entity_Attribute_Source_Options
{
    protected $_optionList = array(
        'url' => 'Redirect to Url',
        'page' => 'Redirect To Cms Page',
        'child_last' => 'Redirect To Last Child',
        'child_first' => 'Redirect To First Child',
    );
}
