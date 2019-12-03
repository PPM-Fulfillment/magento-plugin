<?php
namespace Ppm\Fulfillment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Listens for order submission and also ensures it is submitted to Ppm Fulfillment
 */
class SubmitOrderObserver implements ObserverInterface {

  public function __construct() {
  }

  /**
   * @param \Magento\Framework\Event\Observer $observer
   */
  public function execute(Observer $observer) {
    $order = $observer->getEvent()->getOrder();
    $ppmApiKey = $order->getStore()->getWebsite()->getPpmApiKey();
    $ppmOwnerCode = $order->getStore()->getWebsite()->getPpmOwnerCode();
    $ppmApiUrl = $order->getStore()->getWebsite()->getPpmApiUrl();

    $ppmItems = array();

    foreach ($order->getAllItems() as $item) {
      if ($item->getProduct()->getFulfilledByPpm() && !empty($item->getProduct()->getPpmMerchantSku())) {
        $ppmItems[] = array(
          "ProductId" => $item->getProduct()->getPpmMerchantSku(),
          "Description" => $item->getProduct()->getName(),
          "Quantity" => $item->getQtyToShip(),
        );
      }
    }

    $ppmItemInOrder = count($ppmItems) != 0;

    // Break early if we don't have PPM API keys or Owner Codes
    if (empty($ppmApiKey) || empty($ppmOwnerCode) || !$ppmItemInOrder || empty($ppmApiUrl)) {
      return;
    } else {
      $order->setFulfilledByPpm(true);
      $order->save();
    }

    $orderId = $order->getRealOrderId();
    $shippingAddress = $order->getShippingAddress();
    $shipToName = $shippingAddress->getName();
    $shipToCompany = $shippingAddress->getCompany();
    $shipToTelephone = $shippingAddress->getTelephone();
    $shipToEmail = $shippingAddress->getEmail();
    $city = $shippingAddress->getCity();
    $state = $shippingAddress->getRegionCode();
    $street1 = $shippingAddress->getStreetLine(1);
    $street2 = $shippingAddress->getStreetLine(2);
    $zipCode = $shippingAddress->getPostCode();
    $shippingMethod = "";//$order->getShippingMethod();

    $params = array(
      "ownerCode" => $ppmOwnerCode,
      "orderId" => $orderId,
      "orderNumber" => $orderId,
      "shipToName" => $shipToName,
      "shipToTelephone" => $shipToTelephone,
      "shipToCompany" => $shipToCompany,
      "shipToEmail" => $shipToEmail,
      "address1" => $street1,
      "address2" => $street2,
      "city" => $city,
      "state" => $state,
      "zipCode" => $zipCode,
      "shippingMethod" => $shippingMethod,
      "lineItems" => $ppmItems,
    );

    // Fire off the request to API server
    // $response = \Ppm\Fulfillment\Client::postOrder($params, $ppmApiKey, $ppmApiUrl);
  }
}
?>
