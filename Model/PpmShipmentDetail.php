<?php

namespace Ppm\Fulfillment\Model;

use Magento\Framework\Model\AbstractModel;
use Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail as ResourceModel;

class PpmShipmentDetail extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
