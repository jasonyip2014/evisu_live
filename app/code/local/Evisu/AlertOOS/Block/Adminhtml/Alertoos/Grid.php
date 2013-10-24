<?php

class Evisu_AlertOOS_Block_Adminhtml_Alertoos_Grid extends RonisBT_AdminForms_Block_Adminhtml_Block_Grid_Abstract
{

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('adminforms/block',array('entity_type'=>'alertoos'))->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('first_name')
            ->addAttributeToSelect('last_name')
            ->addAttributeToSelect('store')
            ->addAttributeToSelect('email_address')
            ->addAttributeToSelect('telephone')
            ->addAttributeToSelect('created_at');
            //->addAttributeToSelect('response_at');

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('evisu_alertoos')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));

        $this->addColumn('store',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Store'),
                'index'     => 'store',
                'width'     => '50px',
            ));

        $this->addColumn('sku',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('SKU'),
                'index'     => 'sku',
                'width'     => '50px',
            ));

        $this->addColumn('first_name',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('First Name'),
                'index'     => 'first_name',
                'width'     => '50px',
        ));

        $this->addColumn('last_name',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Last Name'),
                'index'     => 'last_name',
                'width'     => '150px',
                'type'      => 'text',
        ));

        $this->addColumn('email_address',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Email Address'),
                'index'     => 'email_address',
                'width'     => '150px',
                'type'      => 'text',
            ));

        $this->addColumn('telephone',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Telephone'),
                'index'     => 'telephone',
                'width'     => '150px',
                'type'      => 'text',
            ));

        $this->addColumn('created_at',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Created At'),
                'index'     => 'created_at',
                'width'     => '100px',
                'type'      => 'datetime',
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('evisu_alertoos')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('evisu_alertoos')->__('View'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('block_key'=>$this->getRequest()->getParam('block_key'),'store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'entity_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));

        return parent::_prepareColumns();
    }
}
