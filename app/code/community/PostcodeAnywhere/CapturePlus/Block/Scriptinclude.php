<?php

class PostcodeAnywhere_CapturePlus_Block_ScriptInclude extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
		/**
		 * Minified files used by default.
		 * Full files are available (without .min) for debugging.
		 * Only use the minified version if you use JS merging!
		 */
		$this->getLayout()->getBlock('head')->addCss('captureplus/toolkit-3.00.min.css');
        $this->getLayout()->getBlock('head')->addJs('captureplus/address-3.00.min.js');
    }
}
