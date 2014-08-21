<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales orders grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Sales_Invoiceext_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_invoice_gridext');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_invoice_gridext_collection';
    }

    protected function _prepareCollection()
    {
        /* @var $collection Mage_Sales_Model_Resource_Order_Invoice_Gridext_Collection */
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->addFieldToSelect(array('sku', 'evisu_sku', 'qty_ordered', 'base_price', 'base_tax_amount'));
        //$collection->getResource()->getIdFieldName()
        //var_dump(get_class($collection));
        $collection->joinLeft(array('soi' => 'sales/order_item'),
            'main_table.parent_item_id = soi.item_id',
            array('configurable_base_price' => 'base_price', 'configurable_base_tax_amount' => 'base_tax_amount') //'increment_id'
        );

        /*$collection->join(array('sig' => 'sales/invoice_grid'),
            'sig.order_id = main_table.order_id',
            array('order_increment_id', 'order_created_at') //'increment_id'
        );*/
        $collection->addFieldToFilter('main_table.product_type', 'simple');

        $collection->join(array('soa' => 'sales/order_address'),
            'soa.parent_id = main_table.order_id',
            array('country_id') //, 'lastname', 'firstname'
        );
        $collection->addFieldToFilter('soa.address_type', 'shipping');

        $collection->join(array('so' => 'sales/order'),
            'so.entity_id = main_table.order_id',
            array('base_currency_code', 'increment_id', 'created_at', 'base_shipping_incl_tax', 'base_shipping_amount', 'status')
        );


        
                $collection->joinLeft(array('ss' => 'sales/shipment'),
                    'ss.order_id = main_table.order_id',
                    array('shipment_created_at' => 'created_at')
                );
/*
                //var_dump((string)$collection->getSelect());die;
*/
                // add tax

                $collection->joinLeft(array('sot' => 'sales/order_tax'),
                    'sot.order_id = main_table.order_id',
                    array('tax_code' => 'code')
                );
        //echo '<hr/>';
        //echo $collection->getSelect();

       // $collection->getSelect()->group('soi.item_id');

        $this->setCollection($collection);
        //var_dump($collection->getResource()->getIdFieldName());
        //echo (string)parent::_prepareCollection()->getSelect();die;
        //var_dump((string)parent::_prepareCollection()->getSelect());die;

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('sales')->__('Order Date'),
            'index'     => 'created_at',
            'type'      => 'datetime',
            'format'    => 'dd/MM/yyyy',
            'filter_index' => 'so.created_at',
        ));

        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('sales')->__('Order Number'),
            'index'     => 'increment_id',
            'type'      => 'number',
            'filter_index' => 'so.increment_id',
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('customer')->__('SKU'),
            'index'     => 'sku',
            'type'      => 'text',
            'filter_index' => 'main_table.sku',
        ));

        $this->addColumn('evisu_sku', array(
            'header'    => Mage::helper('customer')->__('Evisu SKU'),
            'index'     => 'evisu_sku',
            'type'      => 'text',
            'filter_index' => 'main_table.evisu_sku',
        ));

        $this->addColumn('qty_ordered', array(
            'header'    => Mage::helper('sales')->__('Ordered Qty'),
            'index'     => 'qty_ordered',
            'type'      => 'number',
            'align'     => 'right',
            'filter_index' => 'main_table.qty_ordered',
        ));

        $this->addColumn('base_price', array(
            'header'    => Mage::helper('sales')->__('Unit Price'),
            'index'     => 'base_price',
            'align'     => 'right',
            'renderer'  => 'Mage_Adminhtml_Block_Sales_Invoiceext_Column_Renderer_Price',
            'filter'    => false,
        ));
       /* $this->addColumn('increment_id', array(
            'header'    => Mage::helper('sales')->__('Invoice #'),
            'index'     => 'increment_id',
            'type'      => 'number',
        ));*/

        /*$this->addColumn('firstname', array(
            'header' => Mage::helper('sales')->__('Customer First Name'),
            'index' => 'firstname',
        ));

        $this->addColumn('lastname', array(
            'header' => Mage::helper('sales')->__('Customer Last Name'),
            'index' => 'lastname',
        ));*/

        $this->addColumn('country_id', array(
            'header' => Mage::helper('sales')->__('Country'),
            'index' => 'country_id',
            'type' => 'country',
            'filter_index' => 'soa.country_id',
        ));

        $this->addColumn('base_shipping_incl_tax', array(
            'header'    => Mage::helper('sales')->__('Shipping Cost'),
            'index'     => 'base_shipping_incl_tax',
            'align'     => 'right',
            'renderer'  => 'Mage_Adminhtml_Block_Sales_Invoiceext_Column_Renderer_Shipping',
            'filter'    => false,
        ));

        $this->addColumn('base_tax_amount', array(
            'header'    => Mage::helper('sales')->__('Tax'),
            'index'     => 'base_tax_amount',
            'align'     => 'right',
            'renderer'  => 'Mage_Adminhtml_Block_Sales_Invoiceext_Column_Renderer_Tax',
            'filter'    => false,
        ));

        $this->addColumn('tax_code', array(
            'header'    => Mage::helper('sales')->__('Tax Code'),
            'index'     => 'tax_code',
            //'align'     => 'right',
            'type'      => 'text',
            'filter_index' => 'sot.code',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('sales')->__('Order Status'),
            'index'     => 'status',
            //'align'     => 'right',
            'type'      => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        $this->addColumn('shipment_created_at', array(
            'header'    => Mage::helper('sales')->__('Shipment Date'),
            'index'     => 'shipment_created_at',
            'type'      => 'datetime',
            'format'    => 'dd/MM/yyyy',
            'filter_index' => 'ss.created_at',
        ));



        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}
