<?php

namespace Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Ppm\Fulfillment\Model\PpmShipmentDetail',
            'Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail'
        );
    }
}
