<?php
namespace Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ppm\Fulfillment\Model\PpmShipmentDetail as Model;
use Ppm\Fulfillment\Model\ResourceModel\PpmShipmentDetail as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
