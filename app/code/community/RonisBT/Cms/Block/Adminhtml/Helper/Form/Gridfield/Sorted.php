<?php
class RonisBT_Cms_Block_Adminhtml_Helper_Form_Gridfield_Sorted extends RonisBT_Cms_Block_Adminhtml_Helper_Form_Gridfield
{
    protected function _prepareColumns($grid)
    {
        $grid->addColumn('identifier', array(
            'label' => 'Identifier',
            'class' => 'input-text',
        ));

        $grid->addColumn('label', array(
            'label' => 'Label',
            'class' => 'input-text'
        ));

        $grid->addColumn('position', array(
            'label' => 'Position',
            'class' => 'input-text',
        ));
    }
}