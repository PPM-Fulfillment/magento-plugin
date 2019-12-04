<?php

namespace Ppm\Fulfillment\Block;

use Ppm\Fulfillment\Model\PpmShipmentDetailFactory;

class ShipmentItems extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        PpmShipmentDetailFactory $test
    ) {
        $this->_test = $test;
    }

    public function getPpmShipmentDetails($ids)
   {
      return $this->_test->create()->getCollection()->addFieldToFilter('sales_shipment_item_id', array('in'=> $ids));
   }
}
