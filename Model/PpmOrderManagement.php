<?php
namespace Ppm\Fulfillment\Model;
class PpmOrderManagement {
  /**
   * {@inheritdoc}
   */
  public function markAsShipped($orderId, $tracks)
  {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $order = $objectManager->create('\Magento\Sales\Model\Order')->loadByAttribute('ppm_order_id', $orderId);
    // Check if order can be shipped or has already shipped
    if (! $order->canShip()) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __('You can\'t create a shipment.')
      );
    }
    // Initialize the order shipment object
    $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
    $shipment = $convertOrder->toShipment($order);
    // Loop through order items
    foreach ($order->getAllItems() AS $orderItem) {
      // Check if order item has qty to ship or is virtual
      if (! $orderItem->getQtyToShip() || ! $orderItem->getProduct()->getFulfilledByPpm() || empty($orderItem->getProduct()->getPpmMerchantSku())) {
        continue;
      }
      $qtyShipped = $orderItem->getQtyToShip();
      // Create shipment item with qty
      $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
      // Add shipment item to shipment
      $shipment->addItem($shipmentItem);
    }
    // Register shipment
    $shipment->register();
    $shipment->getOrder()->setIsInProcess(true);
    try {
      // Attempt to save multiple tracking numbers
      if (!empty($tracks)) {
        foreach ($tracks as $track) {
          $data = array(
            'carrier_code' => $track['carrier_code'],
            'title' => $track['title'],
            'track_number' => $track['track_number'],
          );
          $track = $objectManager->create('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($data);
          $shipment->addTrack($track);
        }
      }
      // Save created shipment and order
      $shipment->save();
      $shipment->getOrder()->setPpmOrderStatus("shipped");
      $shipment->getOrder()->save();
      // Send email
      // $objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
      //     ->notify($shipment);
      $shipment->save();
    } catch (\Exception $e) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __($e->getMessage())
      );
    }
    // return 'api GET return the $orderId ' . $orderId;
    return $tracks;
  }
}
?>
