<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikolayk
 * Date: 11/18/13
 * Time: 12:06 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Evisu_Heritage_Helper_Data extends Mage_Core_Helper_Abstract {


    public function getScrollrData($data, $additionalScroll)
    {
        $headerHeight = '140';
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