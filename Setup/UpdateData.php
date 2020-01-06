<?php
namespace Ppm\Fulfillment\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements UpgradeDataInterface
{
  /**
   * @var \Magento\Eav\Setup\EavSetupFactory
   */
  private $eavSetupFactory;
  /**
   * InstallData constructor.
   *
   * @param EavSetupFactory $eavSetupFactory
   */
  public function __construct(EavSetupFactory $eavSetupFactory)
  {
    $this->eavSetupFactory = $eavSetupFactory;
  }
  /**
   * {@inheritdoc}
   */
  public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
  {
    $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
    $eavSetup->updateAttribute(
      \Magento\Catalog\Model\Product::ENTITY,
      'ppm_merchant_sku',
      'note',
      'Only enter a value here if the PPM SKU is different than the Magento Product SKU'
    );
  }
}
?>
