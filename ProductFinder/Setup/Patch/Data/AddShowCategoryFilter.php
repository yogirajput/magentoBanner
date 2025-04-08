<?php
namespace Yogesh\ProductFinder\Setup\Patch\Data;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Api\AttributeManagementInterface;

class AddShowCategoryFilter implements DataPatchInterface
{
    private $_moduleDataSetup;

    private $_eavSetupFactory;
    private AttributeManagementInterface $attributeManagement;
    private Config $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeManagementInterface $attributeManagement,
        Config $config
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
        $this->config = $config;
    }

    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'show_category_filter', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Show Category Filter',
            'input' => 'select',
            'sort_order' => 100,
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'global' => 1,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => null,
            'group' => 'General Information',
            'backend' => ''
        ]);
    }


    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public static function getVersion()
    {
        return '1.0.0';
    }
}