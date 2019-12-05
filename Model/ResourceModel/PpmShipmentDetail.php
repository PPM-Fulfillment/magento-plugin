<?php

namespace Ppm\Fulfillment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PpmShipmentDetail extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('ppm_shipment_detail', 'id');
    }
}
