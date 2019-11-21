<?php
namespace Ppm\Fulfillment\Api;
interface PpmOrderManagementInterface {
	/**
	 * GET for Post api
	 * @param string $orderId
	 * @param string $TrackingNumber
	 * @param string $Carrier
	 * @param mixed $LineItems
	 * @return string
	 */
	public function markAsShipped($orderId, $TrackingNumber, $Carrier, $LineItems);
}
