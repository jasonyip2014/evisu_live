<?php

class Cleargo_Orderexport_Model_Export_Orderperrow extends Cleargo_Orderexport_Model_Export_Abstractcsv
{
    const ENCLOSURE = '"';
    const DELIMITER = ',';
    protected $_order;

    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $orders List of orders of type Mage_Sales_Model_Order or order ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function exportOrders($orders)
    {
        $fileName = 'order_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp);


        foreach ($orders as $order) {
            $order = Mage::getModel('sales/order')->load($order);
            $this->writeOrder($order, $fp);
        }

        fclose($fp);

        return $fileName;
    }

    /**
     * Writes the head row with the column names in the csv file.
     *
     * @param $fp The file handle of the csv file
     */
    protected function writeHeadRow($fp)
    {
        //fputcsv($fp, $this->getHeadRowValues(), self::DELIMITER, self::ENCLOSURE);

        fputcsv($fp, $this->getOutputHeadRowValues(), self::DELIMITER, self::ENCLOSURE);
    }

    /**
     * Writes the row(s) for the given order in the csv file.
     * A row is added to the csv file for each ordered item.
     *
     * @param Mage_Sales_Model_Order $order The order to write csv of
     * @param $fp The file handle of the csv file
     */
    protected function writeOrder($order, $fp)
    {
        $common = $this->getCommonOrderValues($order);

        $orderItems = $order->getItemsCollection();
        $itemInc = 0;
        /*
        foreach ($orderItems as $item)
        {
            if (!$item->isDummy()) {
                $record = array_merge($common, $this->getOrderItemValues($item, $order, ++$itemInc));
                fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
            }
        }*/
        $record = array_merge($common, $this->getAllItemsValues($orderItems,$order),$this->getCustomerValues($order),$this->getInvoiceValues($order));
        //intercept the output column
        $output_columns = array_keys($this->getOutputHeadRowValues());
        foreach ($record as $key=>&$col){
            if (!in_array($key,$output_columns)){
                unset($record[$key]);
            }
        }
        //sort the column
        $record_in_sort = array();
        foreach ($output_columns as $output){
            $record_in_sort[$output] = $record[$output];
        }

        // Zend_debug::dump($record);
        //Zend_debug::dump($record_in_sort);
        // die('test');


        fputcsv($fp, $record_in_sort, self::DELIMITER, self::ENCLOSURE);
    }

    /**
     * take the system config and output selected columns only
     */
    public function getOutputHeadRowValues(){
        $output_columns = Mage::getStoreConfig('order_export/export_orders/output_columns');
        $output_columns = explode(',',$output_columns);
        $all_head_columns = $this->getHeadRowValues();

        if (count($output_columns)>0){
            //flip the key<==> value of the config, intersect with all columns
            return array_intersect_key($all_head_columns,array_flip($output_columns));
        } else {
            return $all_head_columns;
        }
    }

    /**
     * Returns the head column names.
     *
     * @return Array The array containing all column names
     */
    public  function getHeadRowValues()
    {
        return array(

            'created_at' =>'Order Date',
            'customer_created_at'=>'Member Registration Date',
            'customer_confirmation'=> 'Member Confirmation Date',
            'customer_no_newsletter'=>'Member not receive promotion message',
            'increment_id' =>'Order Number',
            'invoice_no'=>'Invoice Number',
            'billing_name'=>'Billing Name',
            'shipping_name'=>'Shipping Name',
            'customer_email'=>'Email',
            'customer_mobile'=>'Telephone',

            'purchased_from' => 'Order Purchased From',
            'payment_method' => 'Order Payment Method',
            'cc_type' => 'Credit Card Type',


            "order_currency_code"=>"Order Currency Code",

            'customer_name' => 'Customer Name',
            'item_name'=>'Item Name',
            'item_status'=>'Item Status',
            'item_sku'=>'Item SKU',
            'item_options'=>'Item Options',
            'item_original_price'=>'Item Original Price',
            'item_price'=>'Item Price',
            'item_qty_ordered'=>'Item Qty Ordered',
            'item_qty_invoiced'=>'Item Qty Invoiced',
            'item_qty_shipped'=>'Item Qty Shipped',
            'item_qty_canceled'=>'Item Qty Canceled',
            'item_qty_refunded'=>'Item Qty Refunded',
            'item_tax'=>'Item Tax',
            'item_discount'=>'Item Discount',
            'item_total'=>'Item Total',
            'item_count'=>'Order Item Increment',

            'userselect'=>'User Select',
            'shipping_method' => 'Shipping Method',

            'billing_company'=>'Billing Company',
            'billing_street'=>'Billing Street',
            'billing_zipcode'=>'Billing Zip',
            'billing_city'=>'Billing City',
            'billing_state'=>'Billing State',
            'billing_state_name'=>'Billing State Name',
            'billing_country_id'=>'Billing Country',
            'billing_country'=>'Billing Country Name',
            'billing_telephone'=>'Billing Phone Number',

            'shipping_company'=>'Shipping Company',
            'shipping_street'=>'Shipping Street',
            'shipping_zipcode'=>'Shipping Zip',
            'shipping_city'=>'Shipping City',
            'shipping_state'=>'Shipping State',
            'shipping_state_name'=>'Shipping State Name',
            'shipping_country_id'=>'Shipping Country',
            'shipping_country'=>'Shipping Country Name',
            'shipping_telephone'=>'Shipping Phone Number',

            'byselfstorename'=> 'Delivery Branch Name',
            'byselfstoreid'=>'Delivery Branch Code',

            'subtotal' => 'Order Subtotal',
            'tax' => 'Order Tax',
            'shipping_cost' => 'Order Shipping',
            'shipping_cost_per_line' => 'Order Shipping fee (Per Line Item)',
            'discount_amount' => 'Order Discount',
            'grand_total' => 'Order Grand Total',
            'base_grand_total' => 'Order Base Grand Total',
            'total_paid' => 'Order Paid',
            'total_refunded' => 'Order Refunded',
            'total_due' => 'Order Due',
            'total_qty_ordered' => 'Total Qty Items Ordered',
            'real_grand_total'=>'Grand Total(without redemption)',

            'status' =>'Order Status',
            'status_last_updated' => 'Status Last Update Day',





        );
    }

    /**
     * Returns the values which are identical for each row of the given order. These are
     * all the values which are not item specific: order data, shipping address, billing
     * address and order totals.
     *
     * @param Mage_Sales_Model_Order $order The order to get values from
     * @return Array The array containing the non item specific values
     */
    protected function getCommonOrderValues($order)
    {
        $shippingAddress = !$order->getIsVirtual() ? $order->getShippingAddress() : null;
        $billingAddress = $order->getBillingAddress();

        $byselfstorename ='';
        $byselfstoreid = $shippingAddress ? $shippingAddress->getData("byselfstoreid") : '';
        if ($byselfstoreid){
            $suep_shop = Mage::getModel('suepshop/suepshop')->load($byselfstoreid,'shop_code');
            if ($suep_shop){
                $byselfstorename = $suep_shop->getName();
            }
        }
        $status_last_updated = '';
        $history = Mage::getModel('sales/order_status_history')->getCollection()
            ->addAttributeToSelect('created_at')
            ->addAttributeToFilter('parent_id', $order->getId())
            ->setOrder('created_at', 'desc')
        ;
        $status_last_updated = $history->getFirstItem()->getCreatedAt();



        return array(
            'increment_id'=>$order->getRealOrderId(),
            'created_at' => date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(strtotime($order->getCreatedAt()))),
            'status' => $order->getStatus(),
            'status_last_updated'=>  date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(strtotime($status_last_updated))),
            'purchased_from' =>$this->getStoreName($order),
            'payment_method' =>$this->getPaymentMethod($order),
            'cc_type'=>$this->getCcType($order),
            'userselect' => $shippingAddress ? $shippingAddress->getUserselect() : '',
            'shipping_method' =>$order->getShippingMethod(),
            'subtotal' =>  $this->formatPrice($order->getData('subtotal'), $order),
            'tax' =>$this->formatPrice($order->getData('tax_amount'), $order),
            'order_currency_code' =>$order->getOrderCurrencyCode(),
            'shipping_cost' =>$this->formatPrice($order->getData('shipping_amount'), $order),
            'discount_amount' =>$this->formatPrice($order->getData('discount_amount'), $order),
            'grand_total' => $this->formatPrice($order->getData('grand_total'), $order),
            'base_grand_total' =>$this->formatPrice($order->getData('base_grand_total'), $order),
            'total_paid' =>$this->formatPrice($order->getData('total_paid'), $order),
            'total_refunded' => $this->formatPrice($order->getData('total_refunded'), $order),
            'total_due' =>$this->formatPrice($order->getData('total_due'), $order),
            'total_qty_ordered' => $this->getTotalQtyItemsOrdered($order),
            'real_grand_total'=> $this->formatPrice($order->getData('subtotal') + $order->getData('shipping_amount') + $order->getData('discount_amount'), $order),
            'customer_name' =>$order->getCustomerName(),
            'customer_email'=>$order->getCustomerEmail(),
            'shipping_name'=>$shippingAddress ? $shippingAddress->getName() : '',
            'shipping_company'=>$shippingAddress ? $shippingAddress->getData("company") : '',
            'shipping_street'=>$shippingAddress ? $this->getStreet($shippingAddress) : '',
            'shipping_zipcode'=>$shippingAddress ? $shippingAddress->getData("postcode") : '',
            'shipping_city'=>$shippingAddress ? $shippingAddress->getData("city") : '',
            'shipping_state'=>$shippingAddress ? $shippingAddress->getRegionCode() : '',
            'shipping_state_name'=>$shippingAddress ? $shippingAddress->getRegion() : '',
            'shipping_country_id'=>$shippingAddress ? $shippingAddress->getCountry() : '',
            'shipping_country'=>$shippingAddress ? Mage::app()->getLocale()->getCountryTranslation($shippingAddress->getCountry()) : '',
            'shipping_telephone'=>$shippingAddress ? $shippingAddress->getData("telephone") : '',
            'billing_name'=>$billingAddress->getName(),
            'billing_company'=>$billingAddress->getData("company"),
            'billing_street'=>$this->getStreet($billingAddress),
            'billing_zipcode'=>$billingAddress->getData("postcode"),
            'billing_city'=>$billingAddress->getData("city"),
            'billing_state'=>$billingAddress->getRegionCode(),
            'billing_state_name'=>$billingAddress->getRegion(),
            'billing_country_id'=>$billingAddress->getCountry(),
            'billing_country'=>$billingAddress->getCountryModel()->getName(),
            'billing_telephone'=>$billingAddress->getData("telephone"),
            'byselfstoreid'=>$shippingAddress ? $byselfstoreid : '',
            'byselfstorename'=>$shippingAddress && $byselfstoreid ? $byselfstorename: ''
        );
    }

    /**
     * Returns the item specific values.
     *
     * @param Mage_Sales_Model_Order_Item $item The item to get values from
     * @param Mage_Sales_Model_Order $order The order the item belongs to
     * @return Array The array containing the item specific values
     */
    protected function getOrderItemValues($item, $order, $itemInc=1)
    {

        //get shipping cost 20140813, by line item
        $shipping_cost_per_line = $order->getData('shipping_amount');

        return array(
            'item_count'=>$itemInc,
            'item_name'=>$item->getName(),
            'item_status'=>$item->getStatus(),
            'item_sku'=>$this->getItemSku($item),
            'item_options'=>$this->getItemOptions($item),
            'item_original_price'=>$this->formatPrice($item->getOriginalPrice(), $order),
            'item_price'=>$this->formatPrice($item->getData('price'), $order),
            'item_qty_ordered'=>(int)$item->getQtyOrdered(),
            'item_qty_invoiced'=>(int)$item->getQtyInvoiced(),
            'item_qty_shipped'=>(int)$item->getQtyShipped(),
            'item_qty_canceled'=>(int)$item->getQtyCanceled(),
            'item_qty_refunded'=>(int)$item->getQtyRefunded(),
            'item_tax'=>$this->formatPrice($item->getTaxAmount(), $order),
            'item_discount'=>$this->formatPrice($item->getDiscountAmount(), $order),
            'item_total'=>$this->formatPrice($this->getItemTotal($item), $order),

            'shipping_cost_per_line' =>$this->formatPrice($shipping_cost_per_line, $order),

        );
    }

    /**
     * Returns all items with Concatenate value
     * @param $items
     * @return array
     */
    protected function getAllItemsValues($items,$order){
        $result = array();
        $data = array();
        $i = 1;
        foreach ($items as $key=>$item){
            $data = array(
                'item_count'=>1,
                'item_name'=>$item->getName(),
                'item_status'=>$item->getStatus(),
                'item_sku'=>$this->getItemSku($item),
                'item_options'=>$this->getItemOptions($item),
                'item_original_price'=>$this->formatPrice($item->getOriginalPrice(), $order),
                'item_price'=>$this->formatPrice($item->getData('price'), $order),
                'item_qty_ordered'=>(int)$item->getQtyOrdered(),
                'item_qty_invoiced'=>(int)$item->getQtyInvoiced(),
                'item_qty_shipped'=>(int)$item->getQtyShipped(),
                'item_qty_canceled'=>(int)$item->getQtyCanceled(),
                'item_qty_refunded'=>(int)$item->getQtyRefunded(),
                'item_tax'=>$this->formatPrice($item->getTaxAmount(), $order),
                'item_discount'=>$this->formatPrice($item->getDiscountAmount(), $order),
                'item_total'=>$this->formatPrice($this->getItemTotal($item), $order)
            );
            foreach ($data as $index=>$column){
                if (!isset($result[$index])){
                    $result[$index]='';
                }
                if (!empty($column)){
                    if (is_int($column)){
                        $result[$index] += $column;
                    } else {
                        $result[$index] .= $column.';';
                    }
                }

                if ($i==count($items) ){

                    $result[$index] = rtrim($result[$index],";");
                }

            }
            $i++;

        }

        return $result;
    }

    protected function getCustomerValues($order)
    {

        $data = array();
        if ($order->getCustomerId()){
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
            $isSub = false;
            if ($subscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED){
                $isSub = true;
            }

            $data =  array(
                'customer_created_at'=>date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(strtotime($customer->getCreatedAt()))),
                'customer_confirmation'=> ($customer->getMemberId()? 'Y' : 'N'),
                'customer_mobile'=>$customer->getMobile(),
                'customer_newsletter'=>($isSub? 'Y' : 'N'), //opposite
                'customer_no_newsletter'=>($isSub? '' : 'Y'), //opposite

            );
        }
        return $data;
    }

    protected function getInvoiceValues($order,$item=''){
        $result = array();
        $result['invoice_no'] = '';
        $invoice_nos = array();
        $invoices = $order->getInvoiceCollection();
        foreach ($invoices as $invoice){
            if (!isset($this->_order[$order->getEntityId()]['invoice'])){
                $this->_order[$order->getEntityId()]['invoice'] = array();
            }
            $this->_order[$order->getEntityId()]['invoice'][$invoice->getEntityId()] = $invoice->getIncrementId();
        }

        if ($invoices->count()>0){
            //if pass in $item, output the invoice of that item belongs to
            if ($item){
                $invoice_item = Mage::getModel('sales/order_invoice_item')->load($item->getItemId(),'order_item_id');
                if ($invoice_item->getParentId()){
                    $this_invoice_id = $invoice_item->getParentId();
                    $result['invoice_no'] = $this->_order[$order->getEntityId()]['invoice'][$this_invoice_id];
                }
            } else {
                //else concatenate all invoices into one cell
                $invoice_nos = $this->_order[$order->getEntityId()]['invoice'];
                $result['invoice_no'] = implode(';',$invoice_nos);
            }

        }

        return $result;
    }

}
?>