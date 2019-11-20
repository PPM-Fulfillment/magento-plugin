<?php
namespace Ppm\Fulfillment\Api;
interface PpmOrderManagementInterface {
  /**
   * GET for Post api
   * @param string $orderId
   * @param mixed $tracks
   * @return string
   */
  public function markAsShipped($orderId, $tracks);
}
?>
