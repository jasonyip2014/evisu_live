<?php
/**
 * iKantam
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade InstagramConnect to newer
 * versions in the future.
 *
 * @category    Ikantam
 * @package     Ikantam_InstagramConnect
 * @copyright   Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Ikantam_InstagramConnect_Block_Blog extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $cacheId = md5('Ikantam_InstagramConnect_Block_Blog');
        $this->addData(array(
            'cache_lifetime'    => 3600 * 6 ,
            'cache_tags'        => array('blog_instagram'),
            'cache_key'         => 'INSTAGRAM_' . $cacheId,
        ));
    }

    private $images = null;

	public function showImages()
	{
        return Mage::getStoreConfig('ikantam_instagramconnect/module_options/blog');
	}
    public function getFollowUrl()
    {
        return Mage::getStoreConfig('ikantam_instagramconnect/module_options/follow_url');
    }
	
	/**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getImages()
    {
        if(is_null($this->images))
        {
            $hl = Mage::helper('instagramconnect/image');
            $this->images = $hl->getUserImages();
        }
        return $this->images;
    }
	
}
