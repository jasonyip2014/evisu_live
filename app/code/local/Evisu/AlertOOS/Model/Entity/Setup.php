<?php

class Evisu_AlertOOS_Model_Entity_Setup extends Mage_Eav_Model_Entity_Setup
{
    public function getDefaultEntities()
    {
        return array(
            'alertoos' => array(
                'entity_model'      => 'adminforms/block',
                'attribute_model'   => 'adminforms/entity_attribute',
                'additional_attribute_table' => 'adminforms/eav_attribute',
                'attributes'   => array()
            )
        );
    }
}
