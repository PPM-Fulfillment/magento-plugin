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
    /* VERSION 0.0.3 */
    if (version_compare($context->getVersion(), "0.0.3", "<")) {
      /*
       * add table `ppm_shipment_detail`
       * Docker: "http://docker.for.mac.localhost:3000/orders"
       * Beta: "https://ppm-beta.agilx.com/api/External/ThirdPartyOrders"
       */
      $tableName = $setup->getTable('ppm_shipment_detail');
      if ($setup->getConnection()->isTableExists($tableName) != true) {
        $table = $setup->getConnection()
        ->newTable($tableName)
        ->addColumn(
          'id',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
          ],
          'ID'
        )
        ->addColumn(
          'ppm_merchant_sku',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          null,
          ['nullable' => false, 'default' => ''],
          'PPM SKU'
        )
        ->addColumn(
          'serial_number',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          null,
          ['nullable' => false, 'default' => ''],
          'Serial Number'
        )
        ->addColumn(
          'lot_number',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          null,
          ['nullable' => false, 'default' => ''],
          'Lot Number'
        )
        ->addColumn(
          'quantity',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          ['unsigned'=>true, 'nullable'=>false, 'default' => '0'],
          'Quantity'
        )
        ->addColumn(
          'sales_shipment_item_id',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          ['unsigned'=>true, 'nullable'=>true],
          'Sales Order Item Id'
        )
        ->addForeignKey(
          $setup->getFkName(
            'ppm_shipment_detail',
            'sales_shipment_item_id',
            'sales_shipment_item',
            'entity_id'
          ),
          'sales_shipment_item_id',
          $setup->getTable('sales_shipment_item'),
          'entity_id',
          // When the parent is deleted, delete the row with foreign key
          \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )
        ->addColumn(
          'sales_shipment_track_id',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          ['unsigned'=>true, 'nullable'=>false, 'default' => '0'],
          'Sales Shipment Track Id'
        )
        ->addForeignKey(
          $setup->getFkName(
            'ppm_shipment_detail',
            'sales_shipment_track_id',
            'sales_shipment_track',
            'entity_id'
          ),
          'sales_shipment_track_id', // New table column
          $setup->getTable('sales_shipment_track'), // Reference Table
          'entity_id', // Reference Table Column
          // When the parent is deleted, delete the row with foreign key
          \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )

        ->setComment('Shipment Serial Number Table')
        ->setOption('type', 'InnoDB')
        ->setOption('charset', 'utf8');
        $setup->getConnection()->createTable($table);
      }
    }

    /* PUT SUBSEQUENT UPGRADES HERE, GOING DOWN LINEARLY WITH VERSION */
    $setup->endSetup();
  }
}
