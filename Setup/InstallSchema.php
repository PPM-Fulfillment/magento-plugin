<?php

namespace Ppm\Fulfillment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
/**
 * Class InstallSchema
 *
 * @package PPM\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function install(
    SchemaSetupInterface $setup,
    ModuleContextInterface $context
  ) {
    $installer = $setup;
    $installer->startSetup();

    /*
     * add column `fulfilled_by_ppm` to `sales_order`
     */
    $installer->getConnection()->addColumn(
      $installer->getTable(
        'sales_order'
      ),
      'fulfilled_by_ppm',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
        'comment' => 'Fulfilled By PPM',
        'default' => 0
      ]
    );
    /*
     * add column `ppm_order_status` to `sales_order`
     */
    $installer->getConnection()->addColumn(
      $installer->getTable(
        'sales_order'
      ),
      'ppm_order_status',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length'=> 255,
        'comment' => 'PPM Order Status'
      ]
    );
    $installer->getConnection()->addColumn(
    /*
     * add column `ppm_order_id` to `sales_order`
     */
      $installer->getTable(
        'sales_order'
      ),
      'ppm_order_id',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length'=> 255,
        'comment' => 'PPM Order ID'
      ]
    );
    /*
     * add column `ppm_api_key` to `store_website`
     */
    $installer->getConnection()->addColumn(
      $installer->getTable(
        'store_website'
      ),
      'ppm_api_key',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'comment' => 'PPM Store API Key'
      ]
    );
    /*
     * add column `ppm_owner_code` to `store_website`
     */
    $installer->getConnection()->addColumn(
      $installer->getTable(
        'store_website'
      ),
      'ppm_owner_code',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'comment' => 'PPM Owner Code'
      ]
    );

    // Get ppm_shipment_detail table
    $tableName = $installer->getTable('ppm_shipment_detail');
    // Check if the table already exists
    if ($installer->getConnection()->isTableExists($tableName) != true) {
        // Create ppm_shipment_detail table
        $table = $installer->getConnection()
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
            ->addForeignKey( // Add foreign key for table entity
                $installer->getFkName(
                    'ppm_shipment_detail', // New table
                    'sales_shipment_item_id', // Column in New Table
                    'sales_shipment_item', // Reference Table
                    'entity_id' // Column in Reference table
                ),
                'sales_shipment_item_id', // New table column
                $installer->getTable('sales_shipment_item'), // Reference Table
                'entity_id', // Reference Table Column
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
            ->addForeignKey( // Add foreign key for table entity
                $installer->getFkName(
                    'ppm_shipment_detail', // New table
                    'sales_shipment_track_id', // Column in New Table
                    'sales_shipment_track', // Reference Table
                    'entity_id' // Column in Reference table
                ),
                'sales_shipment_track_id', // New table column
                $installer->getTable('sales_shipment_track'), // Reference Table
                'entity_id', // Reference Table Column
                // When the parent is deleted, delete the row with foreign key
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )

            ->setComment('Shipment Serial Number Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);
    }


    $installer->endSetup();
  }
}
?>
