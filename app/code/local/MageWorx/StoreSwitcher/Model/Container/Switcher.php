<?php
class MageWorx_StoreSwitcher_Model_Container_Switcher extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get customer identifier from cookies
     *
     * @return string
     */
    protected function _getIdentifier()
    {
        return $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');
    }

    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        return 'GEOIP_STORE_SWITCHER' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
    }

    /**
     * Render block content
     *
     * @return string
     */
    protected function _renderBlock()
    {
        $blockClass = $this->_placeholder->getAttribute('block');

        $template = $this->_placeholder->getAttribute('template');
        $block = new $blockClass;
        $block->setTemplate($template);
        return $block->toHtml();
    }

    // clearing cache if return false;
    public function applyWithoutApp(&$content)
    {
        if(Mage::helper('mwgeoip')->getCookie('geoip_clear_cache'))
        {
            setcookie("geoip_clear_cache", false, 0, '/');
            return false;
        }
        return parent::applyWithoutApp($content);
    }
}