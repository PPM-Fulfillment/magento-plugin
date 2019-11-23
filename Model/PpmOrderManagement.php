<?php
namespace Ppm\Fulfillment\Model;
// {
//   "OrderId": "string",
//   "TrackingNumber": "string",
//   "Carrier": "string",
//   "LineItems": [
//     { "ProductId": "12345", Quantity: 1, "LotNumber": "12345", "SerialNumber": "ABCXYZ" },
//     { "ProductId": "56789", Quantity: 45, "LotNumber": "678", "SerialNumber": "" },
//   ]
// }
class PpmOrderManagement {
  /**
   * {@inheritdoc}
   */
  public function markAsShipped($OrderId, $TrackingNumber, $Carrier, $LineItems)
  {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $order = $objectManager->create("\Magento\Sales\Model\Order")->loadByIncrementId($OrderId);

    // Initialize the order shipment object
    $convertOrder = $objectManager->create("Magento\Sales\Model\Convert\Order");
    $shipment = $convertOrder->toShipment($order);

    // Build sales_order_track record
    $data = array(
      "carrier_code" => $Carrier,
      "title" => $Carrier,
      "track_number" => $TrackingNumber,
    );
    $track = $objectManager->create("Magento\Sales\Model\Order\Shipment\TrackFactory")->create()->addData($data);
    $shipment->addTrack($track);

    // Loop through order items
    foreach ($order->getAllItems() AS $orderItem) {
      $qty = 0;
      foreach ($LineItems as $lineItem) {
        if ($lineItem['ProductId'] == $orderItem->getProduct()->getPpmMerchantSku()) {
          // $hasEmptySerial = empty($lineItem['SerialNumber']);
          // $lineQuantity = $hasEmptySerial ? $lineItem['Quantity'] : 1;
          // $qty += $lineQuantity;
          if(empty($lineItem['SerialNumber'])) {
            $qty += $lineItem['Quantity'];
          } else {
            $qty ++;
          }

          $detailData = array(
            "serial_number" => $lineItem['SerialNumber'],
            "lot_number" => $lineItem['LotNumber'],
            "quantity" => $lineItem['Quantity'],
          );
          $detail = $objectManager->create("Ppm\Fulfillment\Model\PpmShipmentDetailFactory")->create()->addData($detailData);

        }
      }
      $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qty);
      $shipment->addItem($shipmentItem);

      unset($shipmentItem);

      unset($qty);
    }

    // Register shipment
    $shipment->register();
    $shipment->getOrder()->setIsInProcess(true);

    // try {
    //   $shipment->save();
    //   $shipment->getOrder()->setPpmOrderStatus("shipped");
    //   $shipment->getOrder()->save();
    //   // Send email
    //   // $objectManager->create("Magento\Shipping\Model\ShipmentNotifier")
    //   //     ->notify($shipment);
    //   $shipment->save();
    // } catch (\Exception $e) {
    //   throw new \Magento\Framework\Exception\LocalizedException(
    //     __($e->getMessage())
    //   );
    // }
    return "api GET return the $OrderId ";
  }
}
