<?php
namespace Yogesh\ProductFinder\Setup\Patch\Data;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Api\AttributeManagementInterface;

class AddRoomType implements DataPatchInterface
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

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'room_type', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Room Type',
            'input' => 'select',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => true,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false,
        ]);
        $this->assignToAllAttributeSets('room_type');
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'blind_type', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Blind Type',
            'input' => 'select',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => true,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false,
        ]);
        $this->assignToAllAttributeSets('blind_type');
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