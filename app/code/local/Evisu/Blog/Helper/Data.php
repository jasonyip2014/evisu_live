<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 12/13/13
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_Blog_Helper_Data extends Mage_Core_Helper_Abstract {

    private $imageTypeByPosition = array(
        0 => 'list_big_landscape_image',
        1 => 'list_portrait_image',
        2 => 'list_small_landscape_image',
        3 => 'list_small_image',
        4 => 'list_portrait_image',
        5 => 'list_big_landscape_image',
        6 => 'list_small_image',
        7 => 'list_small_image',
        8 => 'list_small_landscape_image',
    );

    private $showTwitter = array(
        0 => false,
        1 => false,
        2 => true,
        3 => true,
        4 => false,
        5 => true,
        6 => false,
        7 => true,
        8 => false,
    );


    public function getTopPost()
    {
        $topPost = null;
        $posts = Mage::getResourceModel('wordpress/post_collection')
            ->addIsPublishedFilter()
            ->setOrderByPostDate('asc');

        foreach($posts as $post)
        {
            /* @var $post Fishpig_Wordpress_Model_Post */
            if($post->getMetaValue('Display on top') == 'Yes')
            {
                $topPost = $post;
                break;
            }
        }
        return $topPost;

    }

    public function getImageUrl($post, $imageCode)
    {
        $imageId = $post->getMetaValue('kd_' . $imageCode .'_post_id');
        $image = Mage::getModel('wordpress/Image')->load($imageId);
        $image->getFullSizeImage();
        return $image->getFullSizeImage(); //Mage::helper('evisu_blog/image')->init($image->getFullSizeImage())->resize(200, 300);
    }

    public function getSocialShareData(Fishpig_Wordpress_Model_Post_Abstract $post)
    {
        $data = new Varien_Object();
        $data->setTitle(urlencode($post->getPostTitle()));
        $data->setUrl(urlencode($post->getPermalink()));
        $data->setSummary(urlencode($post->getMetaValue('Short Description')));
        if (($_image = $post->getFeaturedImage()) !== false)
        {
            $data->setImage(urlencode($_image->getFullSizeImage()));
        }
        return $data;
    }

    //get list grid image type by position
    public function getListImageType($position)
    {
        return $this->imageTypeByPosition[$position % count($this->imageTypeByPosition)];
    }


    public function showTwitter($position)
    {
        return $this->showTwitter[$position % count($this->showTwitter)];
    }

    public function getViewType()
    {
        $viewType = 'grid';
        if(Mage::app()->getRequest()->getParam('view') == 'list')
        {
            $viewType = 'list';
        }
        return $viewType;
    }

    public function getListViewUrl()
    {
        $helper = Mage::helper('core/url');
        return $helper->addRequestParam($helper->removeRequestParam($helper->getCurrentUrl(),'view'), array('view' => 'list'));
    }

    public function getGridViewUrl()
    {
        $helper = Mage::helper('core/url');
        return $helper->addRequestParam($helper->removeRequestParam($helper->getCurrentUrl(),'view'), array('view' => 'grid'));
    }
}