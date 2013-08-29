<?php

class RonisBT_Cms_Model_Entity_Attribute_Backend_Serialized_Array extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Unset array element with '__empty' key
     */
    public function beforeSave($object)
    {
        // parent::beforeSave() is not called intentionally
        $attrCode = $this->getAttribute()->getAttributeCode();
        if ($object->hasData($attrCode)){
            $value = $object->getData($attrCode);
            if (is_array($value)){
                unset($value['__empty']);
            }

            $value = $this->_prepareValue($value);

            $object->setData($attrCode, serialize($value));
        }
    }

    /**
     * Unserialize after saving
     * @param Varien_Object $object
     */
    public function afterSave($object)
    {
        parent::afterSave($object);
        $this->_unserialize($object);
    }

    /**
     * Unserialize after loading
     * @param Varien_Object $object
     */
    public function afterLoad($object)
    {
        parent::afterLoad($object);
        $this->_unserialize($object);
    }

    protected function _prepareValue($value)
    {
        return $value;
    }

    /**
     * Try to unserialize the attribute value
     * @param Varien_Object $object
     */
    protected function _unserialize(Varien_Object $object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        if ($object->getData($attrCode)) {
            try {
                $unserialized = unserialize($object->getData($attrCode));
                $object->setData($attrCode, $unserialized);
            } catch (Exception $e) {
                $object->unsetData($attrCode);
            }
        }
    }

}
