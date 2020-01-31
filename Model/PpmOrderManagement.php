<?php
namespace Ppm\Fulfillment\Model;

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
        $hasPpmSku = $lineItem['ProductId'] == $orderItem->getProduct()->getPpmMerchantSku();
        $hasSku = $lineItem['ProductId'] == $orderItem->getProduct()->getSku();
        if ($hasSku || $hasPpmSku) {
          $hasEmptySerial = empty($lineItem['SerialNumber']);
          $lineQuantity = $hasEmptySerial ? $lineItem['Quantity'] : 1;
          $qty += $lineQuantity;
          $detailData = array(
            'serial_number' => $lineItem['SerialNumber'],
            'lot_number' => $lineItem['LotNumber'],
            'quantity' => $lineQuantity,
            'ppm_merchant_sku' => $lineItem['ProductId']
          );
          $model = $objectManager->create('Ppm\Fulfillment\Model\PpmShipmentDetail');
          $model->setData($detailData);
          $model->_sales_shipment_track = $track;
          $details[] = $model;
          unset($model);
          unset($hasEmptySerial);
          unset($lineQuantity);
        }
        unset($hasPpmSku);
        unset($hasSku);
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
          $product = $itm->getOrderItem()->getProduct();
          $matchesPpmSku = $product->getPpmMerchantSku() == $detail->getPpmMerchantSku();
          $matchesSku = $product->getSku() == $detail->getPpmMerchantSku();
          if ($matchesSku || $matchesPpmSku) {
            $detail->setSalesShipmentItemId($itm->getEntityId());
          }
          unset($matchesSku);
          unset($matchesPpmSku);
          unset($product);
        }
        $detail->setSalesShipmentTrackId($track->getEntityId());
        $detail->save();
      }
      $shipment->getOrder()->setPpmOrderStatus("shipped");
      $shipment->getOrder()->save();
      $shipment->setFulfilledByPpm(true);
      $shipment->save();
    } catch (\Exception $e) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __($e->getMessage())
      );
    }

    return [
      [
        'ShipmentId' => $shipment->getId()
      ]
    ];
  }
}
