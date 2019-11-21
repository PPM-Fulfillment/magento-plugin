<?php
namespace Ppm\Fulfillment\Api;
interface PpmOrderManagementInterface {
  /**
   * GET for Post api
   * @param string $OrderId
   * @param string $TrackingNumber
   * @param string $Carrier
   * @param mixed $LineItems
   * @return string
   */
  public function markAsShipped($OrderId, $TrackingNumber, $Carrier, $LineItems);
}
