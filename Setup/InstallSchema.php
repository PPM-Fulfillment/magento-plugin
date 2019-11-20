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

    $installer->endSetup();
  }
}
?>
