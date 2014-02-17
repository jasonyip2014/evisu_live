<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 8/29/13
 * Time: 9:39 AM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_HomePage_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getVideoUrlByCode($code)
    {
        return 'http://player.vimeo.com/video/' . $code . '?title=0&amp;byline=0&amp;portrait=0&amp;color=b400ff&amp;autoplay=1&player_class=fvideo&api=1';
    }

    public function getScrollrData($data, $additionalScroll)
    {
        $headerHeight = '0';
        $AnimationDataHtml = '';
        if($data)
        {
            foreach(unserialize($data) as $animationRule)
            {
                $AnimationDataHtml .= 'data-' . ($animationRule['scroll'] + $additionalScroll + $headerHeight) . '="' . $animationRule['animation'] . '" ';
            }
        }
        return $AnimationDataHtml;
    }
}