<?php

namespace Ppm\Fulfillment\Model;

use Magento\Framework\Model\AbstractModel;

class PpmShipmentDetail extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail');
    }
}
