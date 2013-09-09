<?php

class RonisBT_TopMenu_Block_Adminhtml_System_Config_Form_Field_Array_Items extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct(){
        $_req = ' <span class="required">*</span>';
        $_hlp = $this->helper('topmenu');

        $_type = $this->_getTypeRenderer()
            ->setClass('input-text required-entry')
            ->setExtraParams('style="width:80px;"');
        $this->addColumn('type',
            array(
                'label'     => $_hlp->__('Type').$_req,
                'style'     => 'width:80px',
                'class'     => 'input-text required-entry',
                'renderer'  => $_type,
            )
        );

        $this->addColumn('key',
            array(
                'label'     => $_hlp->__('Key').$_req,
                'style'     => 'width:80px',
                'class'     => 'required-entry',
            )
        );

        $this->addColumn('sibling',
            array(
                'label'     => $_hlp->__('Next Sibling'),
                'style'     => 'width:80px',
            )
        );

        $this->addColumn('parent',
            array(
                'label'     => $_hlp->__('Parent'),
                'style'     => 'width:80px',
            )
        );

        $this->addColumn('block',
            array(
                'label'     => $_hlp->__('Renderer Type'),
                'style'     => 'width:100px',
            )
        );

        $this->addColumn('title',
            array(
                'label'     => $_hlp->__('Title'),
                'style'     => 'width:150px',
            )
        );

        $this->addColumn('url',
            array(
                'label'     => $_hlp->__('URL'),
                'style'     => 'width:150px',
            )
        );

        $_direct = $this->_getYesNoRenderer()
            ->setClass('input-text')
            ->setExtraParams('style="width:60px;"');
        $this->addColumn('url_rewrite',
            array(
                'label'     => $_hlp->__('URL Rewrite'),
                'style'     => 'width:60px',
                'renderer'  => $_direct,
            )
        );

        $_enabled = $this->_getYesNoRenderer()
            ->setClass('input-text')
            ->setExtraParams('style="width:60px;"');
        $this->addColumn('enabled',
            array(
                'label'     => $_hlp->__('Enabled'),
                'style'     => 'width:60px',
                'renderer'  => $_enabled,
            )
        );

        $this->_addAfter        = false;
        $this->_addButtonLabel  = $_hlp->__('Add item');

        parent::__construct();
    }

    protected function _getTypeRenderer(){
        $select = Mage::app()->getLayout()
            ->createBlock('topmenu/adminhtml_form_field_select', '',
                array('is_render_to_js_template' => true)
            )
            ->setOptions(Mage::getSingleton('topmenu/source_item_type')->toOptionArray());
        return $select;
    }

    protected function _getYesNoRenderer(){
        $select = Mage::app()->getLayout()
            ->createBlock('topmenu/adminhtml_form_field_select', '',
                array('is_render_to_js_template' => true)
            )
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());
        return $select;
    }

    public function getArrayRows(){
        $rows = parent::getArrayRows();

        foreach ($rows as $row){
            foreach ($row->getData() as $key => $value){
                if (!isset($this->_columns[$key]))
                    continue;

                $column = $this->_columns[$key];

                if ($column['renderer'] instanceof Mage_Core_Block_Html_Select)
                    $row->setData(sprintf('option_extra_attr_%s',  $column['renderer']->calcOptionHash($value)), 'selected="selected"');
            }
        }
        return $rows;
    }

}
