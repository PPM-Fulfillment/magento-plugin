<?php
namespace Ppm\Fulfillment\Model\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
/**
 * Listens for order submission and also ensures it is submitted to Ppm Fulfillment
 *
 * Class SubmitOrderObserver
 */
class SubmitOrderObserver implements ObserverInterface
{
  public function __construct()
  {
  }
  /**
   * @param \Magento\Framework\Event\Observer $observer
   */
  public function execute(Observer $observer)
  {
    $order = $observer->getEvent()->getOrder();
    // Do we have any items to submit to Ppm API?
    $ppmItemInOrder = false;
    foreach ($order->getAllItems() as $item) {
      if ($item->getProduct()->getFulfilledByPpm() && !empty($item->getProduct()->getPpmMerchantSku())) {
        $ppmItemInOrder = true;
        break;
      }
    }
    if (empty($order->getStore()->getWebsite()->getPpmApiKey()) || !$ppmItemInOrder) {
      return;
    }
    // $response = $this->outbound->createFulfillmentOrder($order);
    // if (!empty($response)) {
    $order->setPpmOrderStatus("received");
    $order->setFulfilledByPpm(true);
    $order->setPpmOrderId(rand());
    // } else {
    //     $order->setPpmOrderStatus("attempted");
    //     $order->setFulfilledByPpm(false);
    // }
  }
}
?>
