<?php
class RonisBT_AdminForms_Model_Attribute_Backend_File extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Save uploaded file and set its name to
     *
     * @param Varien_Object $object
     */
    public function afterSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());

        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::getBaseDir('media') . DS . 'adminforms' . DS . $this->getAttribute()->getEntity()->getType() . DS;

        try {
            $uploader = Mage::getModel('core/file_uploader', $this->getAttribute()->getName());
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->save($path);

            $object->setData($this->getAttribute()->getName(), $uploader->getUploadedFileName());
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }
            /** @TODO ??? */
            return;
        }
    }
}
