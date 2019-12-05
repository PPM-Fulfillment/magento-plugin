<?php
namespace Ppm\Fulfillment\Api;
interface PpmOrderManagementInterface {
  /**
   * @api
   * @param string $OrderId
   * @param string $TrackingNumber
   * @param string $Carrier
   * @param mixed $LineItems
   * @return mixed[]
   */
  public function markAsShipped($OrderId, $TrackingNumber, $Carrier, $LineItems);
}
