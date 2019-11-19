<?php

namespace Ppm\MagentoFulfillment\Setup;

class Uninstall implements \Magento\Framework\Setup\UninstallInterface {
  protected $eavSetupFactory;

  public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory) {
    $this->eavSetupFactory = $eavSetupFactory;
  }

  public function uninstall(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context) {
    $setup->startSetup();
    $eavSetup = $this->eavSetupFactory->create();
    $eavSetup->removeAttribute(4, 'sorting_attribute');
    $setup->endSetup();
  }
}
?>
