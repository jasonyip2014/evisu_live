<?php
class Evisu_StoreLocator_Model_Observer
{
    public function setLatLngByAddress(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if($block->getEntityType() == 'store_locator_grid')
        {
            if(!$block->getSlLat() || !$block->getSlLong())
            {
                $address = str_replace(' ', '+',$block->getSlAddress());
                //$url ='http://maps.google.com/maps/geo?q=' . $address . '&output=xml&key=' . $key;
                $url ='http://maps.google.com/maps/api/geocode/xml?address=' . $address . '&sensor=false';
                //var_dump($url);
                $xml = simplexml_load_file($url);

                if($xml && $xml->status == "OK")
                {
                    $block->setSlLat((string)$xml->result->geometry->location->lat);
                    $block->setSlLong((string)$xml->result->geometry->location->lng);
                }
            }
        }
        return $this;
    }

}
