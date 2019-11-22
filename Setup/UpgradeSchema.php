<?php

namespace Ppm\Fulfillment\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
  /**
   * {@inheritdoc}
   */
  public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
    $setup->startSetup();

    /* VERSION 0.0.1 */
    if (version_compare($context->getVersion(), "0.0.2", "<")) {
      /*
       * add column `ppm_api_url` to `store_website`
       * Docker: "http://docker.for.mac.localhost:3000/orders"
       * Beta: "https://ppm-beta.agilx.com/api/External/ThirdPartyOrders"
       */
      $setup->getConnection()->addColumn(
        $setup->getTable("store_website"),
        "ppm_api_url",
        [
          "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          "length" => 255,
          "nullable" => false,
          "default" => "https://portal.ppmfulfillment.com/api/External/ThirdPartyOrders",
          "comment" => "PPM API URL"
        ]
      );
    }
    /* PUT SUBSEQUENT UPGRADES HERE, GOING DOWN LINEARLY WITH VERSION */
    $setup->endSetup();
  }
}
