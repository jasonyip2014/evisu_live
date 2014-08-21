<?php

class Cleargo_Orderexport_Model_Export_Lineitem extends Cleargo_Orderexport_Model_Export_Orderperrow
{

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

        foreach ($orderItems as $item){
            if (!$item->getParentItemId()){
                $record = array_merge($common, $this->getOrderItemValues($item, $order, ++$itemInc),$this->getCustomerValues($order),$this->getInvoiceValues($order,$item));
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

                //Zend_debug::dump($record);
               //Zend_debug::dump($record_in_sort);

                fputcsv($fp, $record_in_sort, self::DELIMITER, self::ENCLOSURE);
            }

        }

    }


}
?>