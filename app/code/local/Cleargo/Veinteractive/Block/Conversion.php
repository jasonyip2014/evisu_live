<?php
/* install tracking code
 * @author leo
 */
class Cleargo_Veinteractive_Block_Conversion extends Mage_Core_Block_Template{

    const XML_IS_ACTIVE = 'veinteractive/general/active';
    const XML_CONTAINER_TAG = 'veinteractive/general/container_tag';
    const XML_PIXEL = 'veinteractive/general/pixel';

    /*
     * get store config to check is active
     */
    public function isActive(){
        return (bool)Mage::getStoreConfig(self::XML_IS_ACTIVE);
    }

    public function getContainerTag(){
        return $this->isActive()? Mage::getStoreConfig(self::XML_CONTAINER_TAG) : '';
    }

    public function getPixel(){
        return $this->isActive()? Mage::getStoreConfig(self::XML_PIXEL) : '';
    }




}
