<?php
namespace Ppm\Fulfillment\Model;
#TODO Remove ME
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
      "track_number" => $TrackingNumber
    );
    $track = $objectManager->create("Magento\Sales\Model\Order\Shipment\TrackFactory")->create()->addData($data);
    $shipment->addTrack($track);

    // Loop through order items
    $details = [];
    $shipmentItems = [];
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
#TODO get commected code working and plug in lineItem quantity here
          $detailData = array(
            'serial_number' => $lineItem['SerialNumber'],
            'lot_number' => $lineItem['LotNumber'],
            'quantity' => $lineItem['Quantity'],
            'ppm_merchant_sku' => $lineItem['ProductId']
          );

          $model = $objectManager->create('Ppm\Fulfillment\Model\PpmShipmentDetail');
          $model->setData($detailData);
          $model->_sales_shipment_track = $track;
          $details[] = $model;
          unset($model);
        }
      }

      if ($qty > 0) {
        $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qty);
        $shipmentItems[] = $shipmentItem;
        $shipment->addItem($shipmentItem);
        unset($shipmentItem);
      }
      unset($qty);
    }

    // Register shipment
    $shipment->register();
    $shipment->getOrder()->setIsInProcess(true);

    try {
      $shipment->save();
      foreach ($details as $detail) {
        foreach ($shipmentItems as $itm) {
          if ($itm->getOrderItem()->getProduct()->getPpmMerchantSku() == $detail->getPpmMerchantSku()) {
            $id = strval($itm->getEntityId());
            $detail->setSalesShipmentItemId($itm->getEntityId());
          }
        }
        $detail->setSalesShipmentTrackId($track->getEntityId());
        $detail->save();
      }
      $shipment->getOrder()->setPpmOrderStatus("shipped");
      $shipment->getOrder()->save();
      // Send email
      // $objectManager->create("Magento\Shipping\Model\ShipmentNotifier")
      //     ->notify($shipment);
      $shipment->save();
    } catch (\Exception $e) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __($e->getMessage())
      );
    }
    #TODO RETURN success: true, shipmentID
    return "api GET return the $OrderId " . $id;
  }
}
