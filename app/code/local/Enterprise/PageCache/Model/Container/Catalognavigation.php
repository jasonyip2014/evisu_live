<?php
/**
 * Placeholder container for catalog top navigation block
 */

class Enterprise_PageCache_Model_Container_Catalognavigation extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * @return string
     */
    protected function _getBlockCacheId()
    {
        return $this->_placeholder->getAttribute('short_cache_id');
    }

    /**
     * @return string
     */
    protected function _getCategoryCacheId()
    {
        $shortCacheId = $this->_placeholder->getAttribute('short_cache_id');
        $categoryPath = $this->_placeholder->getAttribute('category_path');
        $categoryId = $this->_getCategoryId();
        if (!$shortCacheId || !$categoryPath) {
            return false;
        }
        return $shortCacheId . '_' . $categoryPath . ($categoryId ? ('_' . $categoryId) : '');
    }

    /**
     * Generate placeholder content before application was initialized and apply to page content if possible
     *
     * @param string $content
     * @return bool
     */
    public function applyWithoutApp(&$content)
    {
        $blockCacheId = $this->_getBlockCacheId();
        $categoryCacheId = $this->_getCategoryCacheId();
        if ($blockCacheId && $categoryCacheId) {
            $blockContent = $this->_loadCache($blockCacheId);
            $categoryUniqueClasses = $this->_loadCache($categoryCacheId);
            if ($blockContent !== false && $categoryUniqueClasses !== false) {
                if ($categoryUniqueClasses != '') {
                    $regexp = '';
                    foreach (explode(' ', $categoryUniqueClasses) as $categoryUniqueClass) {
                        $regexp .= ($regexp ? '|' : '') . preg_quote($categoryUniqueClass);
                    }
                    $blockContent = preg_replace('/(?<=\s|")(' . $regexp . ')(?=\s|")/u', '$1 active', $blockContent);
                }
                $this->_applyToContent($content, $blockContent);
                return true;
            }
        }
        return false;
    }

    /**
     * Generate and apply container content in controller after application is initialized
     *
     * @param string $content
     * @return bool
     */
    public function applyInApp(&$content)
    {
        $blockContent = $this->_renderBlock();
        if ($blockContent === false) {
            return false;
        }

        if (Mage::getStoreConfig(Enterprise_PageCache_Model_Processor::XML_PATH_CACHE_DEBUG)) {
            $debugBlock = new Enterprise_PageCache_Block_Debug();
            $debugBlock->setDynamicBlockContent($blockContent);
            $this->_applyToContent($content, $debugBlock->toHtml());
        } else {
            $this->_applyToContent($content, $blockContent);
        }

        $subprocessor = $this->_processor->getSubprocessor();
        if ($subprocessor) {
            $contentWithOutNestedBlocks = $subprocessor->replaceContentToPlaceholderReplacer($blockContent);
        }

        $this->saveCache($contentWithOutNestedBlocks);
        return true;
    }

    /**
     * Save rendered block content to cache storage
     *
     * @param string $blockContent
     * @return Enterprise_PageCache_Model_Container_Abstract
     */
    public function saveCache($blockContent, $tags = array())
    {
        $blockCacheId = $this->_getBlockCacheId();
        if ($blockCacheId) {
            $categoryCacheId = $this->_getCategoryCacheId();
            if ($categoryCacheId) {
                $categoryUniqueClasses = '';
                $classes = array();
                $classesCount = preg_match_all('/(?<=\s)class="([^"]*?active[^"]*?)"/u', $blockContent, $classes); //RonisBT: fix regexp
                for ($i = 0; $i < $classesCount; $i++) {
                    $classAttribute = $classes[0][$i];
                    $classValue = $classes[1][$i];
                    $classInactive = preg_replace('/(\s+active|active\s+|^active$)/', '', $classAttribute); //RonisBT: fix regexp
                    $blockContent = str_replace($classAttribute, $classInactive, $blockContent);
                    $matches = array();
                    if (preg_match('/(?<=\s|^)nav-[^"]+?(?=\s|$|")/', $classValue, $matches)) { //RonisBT: fix regexp
                        $categoryUniqueClasses .= ($categoryUniqueClasses ? ' ' : '') . $matches[0];
                    }
                }
                $this->_saveCache($categoryUniqueClasses, $categoryCacheId);
            }
            if (!Enterprise_PageCache_Model_Cache::getCacheInstance()->getFrontend()->test($blockCacheId)) {
                $this->_saveCache($blockContent, $blockCacheId);
            }
        }
        return $this;
    }

    /**
     * Render block content
     *
     * @return string
     */
    protected function _renderBlock()
    {
        $block = $this->_getPlaceHolderBlock();
        $renderers = @unserialize($this->_placeholder->getAttribute('renderers')); //RonisBT: TopMenu

        $categoryId = $this->_getCategoryId();
        if (!Mage::registry('current_category') && $categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            Mage::register('current_category', $category);
        }
        //RonisBT: TopMenu begin
        if (is_array($renderers)){
            foreach ($renderers as $key=>$info){
                $blockName = $info[0];
                $blockTemplate = $info[1];
                $renderer = new $blockName;
                $renderer->setTemplate($blockTemplate);
                $block->addMenuRendererBlock($key, $renderer);
            }
        }
        //RonisBT: TopMenu end

        Mage::dispatchEvent('render_block', array('block' => $block, 'placeholder' => $this->_placeholder));

        return $block->toHtml();
    }
}
