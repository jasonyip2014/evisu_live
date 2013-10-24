<?php

class Evisu_StoreLocator_Helper_Adminhtml_Advancedcms_Gridfield extends RonisBT_Cms_Block_Adminhtml_Helper_Form_Gridfield_Sorted
{
    protected function _prepareColumns($grid)
    {
        $grid->addColumn('days', array(
            'label' => 'Days',
            'class' => 'input-text',
        ));

        $grid->addColumn('times', array(
            'label' => 'Times',
            'class' => 'input-text'
        ));

        $grid->addColumn('position', array(
            'label' => 'Position',
            'class' => 'input-text',
        ));
    }
}