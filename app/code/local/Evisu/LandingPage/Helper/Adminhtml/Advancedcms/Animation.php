<?php

class Evisu_LandingPage_Helper_Adminhtml_Advancedcms_Animation extends RonisBT_Cms_Block_Adminhtml_Helper_Form_Gridfield
{
    protected function _prepareColumns($grid)
    {
        $grid->addColumn('scroll', array(
            'label' => 'Scroll (px)',
            'class' => 'input-text',
            'required' => 'true',
            'width' => '100'
        ));

        $grid->addColumn('animation', array(
            'label' => 'Animation CSS',
            'class' => 'input-text',
            'required' => 'true',
        ));
    }
}