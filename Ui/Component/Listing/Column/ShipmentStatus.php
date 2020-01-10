<?php

namespace Ppm\Fulfillment\Ui\Component\Listing\Column;

use \Magento\Sales\Api\ShipmentRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;

class ShipmentStatus extends Column
{
  protected $_shipmentRepository;
  protected $_searchCriteria;

  public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, ShipmentRepositoryInterface $shipmentRepository, SearchCriteriaBuilder $criteria, array $components = [], array $data = [])
  {
    $this->_shipmentRepository = $shipmentRepository;
    $this->_searchCriteria  = $criteria;
    parent::__construct($context, $uiComponentFactory, $components, $data);
  }

  public function prepareDataSource(array $dataSource)
  {
    if (isset($dataSource['data']['items'])) {
      foreach ($dataSource['data']['items'] as & $item) {

        $shipment  = $this->_shipmentRepository->get($item["entity_id"]);
        if ($shipment->getFulfilledByPpm() == true) {
          $ppm_status = 'Yes';
        } else {
          $ppm_status = 'No';
        }

        $item[$this->getData('name')] = $ppm_status;
      }
    }

    return $dataSource;
  }
}
