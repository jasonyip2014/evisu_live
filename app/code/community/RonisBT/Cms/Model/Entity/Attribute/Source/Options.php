<?php
class RonisBT_Cms_Model_Entity_Attribute_Source_Options extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_optionList = array();

    /**
     * Retrieve All options
     *
     * @return array
     *      array(
     *          array('value' => value, 'label' => label)
     *      )
     */
    public function getAllOptions()
    {
        if (null === $this->_options) {
            $this->_options = $this->toOptionArray();
        }

        return $this->_options;
    }

    /**
     * @return array
     *      array(
     *          array('value' => value, 'label' => label)
     *      )
     */
    public function toOptionArray()
    {
        $options = array();
        foreach ($this->_getOptionList() as $value => $label) {
            $options[] = array(
                'value' => $value,
                'label' => $label,
            );
        }

        return $options;
    }

    /**
     * @return array - array(key => value, ...)
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->getAllOptions() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * @return array - array(key => value, ...)
     */
    protected function _getOptionList()
    {
        return $this->_optionList;
    }
}
