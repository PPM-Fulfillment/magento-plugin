<?php
namespace Ppm\Fulfillment\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class InstallData implements InstallDataInterface
{
  /**
   * @var \Magento\Eav\Setup\EavSetupFactory
   */
  private $eavSetupFactory;
  /**
   * @var \Magento\Framework\App\Config\Storage\WriterInterface
   */
  private $configWriter;
  /**
   * InstallData constructor.
   *
   * @param EavSetupFactory $eavSetupFactory
   * @param WriterInterface $configWriter
   */
  public function __construct(EavSetupFactory $eavSetupFactory, WriterInterface $configWriter)
  {
    $this->eavSetupFactory = $eavSetupFactory;
    $this->configWriter = $configWriter;
  }
  /**
   * {@inheritdoc}
   */
  public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
  {
    $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
    $eavSetup->addAttribute(
      \Magento\Catalog\Model\Product::ENTITY,
      'fulfilled_by_ppm',
      [
        'group' => 'PPM Fulfillment',
        'type' => 'int',
        'backend' => 'Magento\Catalog\Model\Product\Attribute\Backend\Boolean',
        'frontend' => '',
        'label' => 'Fulfilled By PPM?',
        'input' => 'boolean',
        'class' => '',
        'source' => 'Magento\Catalog\Model\Product\Attribute\Source\Boolean',
        'global' => true,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'default' => '0',
        'visible_on_front' => false,
        'is_used_in_grid' => true,
        'is_visible_in_grid' => true,
        'is_filterable_in_grid' => true,
        'apply_to' => 'simple'
      ]
    );
    $eavSetup->addAttribute(
      \Magento\Catalog\Model\Product::ENTITY,
      'ppm_merchant_sku',
      [
        'group' => 'PPM Fulfillment',
        'type' => 'varchar',
        'label' => 'PPM Override SKU',
        'input' => 'text',
        'global' => true,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'default' => '',
        'visible_on_front' => false,
        'is_used_in_grid' => true,
        'is_visible_in_grid' => false,
        'is_filterable_in_grid' => false,
        'note' => 'Add a value here only if PPM SKU is different than the SKU for this Product. This is the SKU that will be sent to PPM in that case.',
        'apply_to' => 'simple'
      ]
    );
  }
}
