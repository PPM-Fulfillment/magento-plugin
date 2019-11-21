<?php
namespace Ppm\Fulfillment\Model;
// {
//   “OrderId”: “string”,
//   “TrackingNumber”: “string”,
//   “Carrier”: “string”,
//   “LineItems”: [
//     { “ProductId”: “12345”, Quantity: 1, “LotNumber”: “12345”, “SerialNumber”: “ABCXYZ” },
//     { “ProductId”: “56789”, Quantity: 45, “LotNumber”: “678”, “SerialNumber”: “” },
//   ]
// }
class PpmOrderManagement {
	/**
	 * {@inheritdoc}
	 */
	public function markAsShipped($orderId, $TrackingNumber, $Carrier, $LineItems)
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$order = $objectManager->create(‘\Magento\Sales\Model\Order’)->load($orderId);
		// Check if order can be shipped or has already shipped
		if (! $order->canShip()) {
		    throw new \Magento\Framework\Exception\LocalizedException(
		                    __(’You can\‘t create an shipment.’)
		                );
		}
		// Initialize the order shipment object
		$convertOrder = $objectManager->create(‘Magento\Sales\Model\Convert\Order’);
		$shipment = $convertOrder->toShipment($order);
		// Loop through order items
		foreach ($order->getAllItems() AS $orderItem) {
			foreach ($LineItems as $lineItem) {
				if ($lineItem[‘ProductId’] == $orderItem->getProduct()->getPpmMerchantSku()) {
					$qtyShipped = $lineItem[‘Quantity’];
					$shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
					$shipment->addItem($shipmentItem);
				}
			}
		}
		// Register shipment
		$shipment->register();
		$shipment->getOrder()->setIsInProcess(true);
		try {
				// Attempt to save multiple tracking numbers
		    $data = array(
		      ‘carrier_code’ => ‘fedex’,
		      ‘title’ => $Carrier,
		      ‘track_number’ => $TrackingNumber,
		    );
				$track = $objectManager->create(‘Magento\Sales\Model\Order\Shipment\TrackFactory’)->create()->addData($data);
		    $shipment->addTrack($track);
		    // Save created shipment and order
		    $shipment->save();
				$shipment->getOrder()->setPpmOrderStatus(“shipped”);
		    $shipment->getOrder()->save();
		    // Send email
		    // $objectManager->create(‘Magento\Shipping\Model\ShipmentNotifier’)
		    //     ->notify($shipment);
		    $shipment->save();
		} catch (\Exception $e) {
		    throw new \Magento\Framework\Exception\LocalizedException(
		                    __($e->getMessage())
		                );
		}
		return ‘api GET return the $orderId ’ . $orderId;
	}
}
