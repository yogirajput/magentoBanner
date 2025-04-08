<?php
namespace Yogesh\ProductFinder\Setup\Patch\Data;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Api\AttributeManagementInterface;

class AddMaterialCategory implements DataPatchInterface
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
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'material_category');
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'material_category', [
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'label' => 'Material Category',
            'input' => 'multiselect',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'source' => 'Yogesh\ProductFinder\Model\Product\MaterialCategory\Option',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false,
        ]);
        $this->assignToAllAttributeSets('material_category');
    }

    /**
     * Assign Attribute to all Attribute sets
     *
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function assignToAllAttributeSets($attributeCode)
    {
        $this->_moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_moduleDataSetup]);
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
        foreach ($attributeSetIds as $attributeSetId) {
            if ($attributeSetId) {
                $attributeGroupId = $this->config->getAttributeGroupId($attributeSetId, 'General');
                $this->attributeManagement->assign(
                    Product::ENTITY,
                    $attributeSetId,
                    $attributeGroupId,
                    $attributeCode,
                    999
                );
            }
        }
        $this->_moduleDataSetup->getConnection()->endSetup();
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