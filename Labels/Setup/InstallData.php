<?php
namespace Yogesh\Labels\Setup;

use Magento\Eav\Setup\EavSetup;  
use Magento\Eav\Setup\EavSetupFactory;  
use Magento\Framework\Setup\InstallDataInterface;  
use Magento\Framework\Setup\ModuleContextInterface;  
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**  
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface  
{  
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
    	$setup->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        /*
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'first_stamp', [
            'type' => 'varchar',
            'backend' => '',
            'frontend' => '',
            'label' => 'First stamp image',
            'input' => 'media_image',
            'class' => '',
            'source' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable',
            'group' =>  'image-management'
            ]
        );
        */
    
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'second_stamp', [
            'type' => 'varchar',
            'backend' => '',
            'frontend' => '',
            'label' => 'Second stamp image',
            'input' => 'media_image',
            'class' => '',
            'source' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable',
            'group' =>  'image-management'
            ]
        );

        $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY, 'enable_first_stamp', [
            'group' => 'General',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Enable first stamp',
            'input' => 'boolean',
            'class' => '',
            'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => 1,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY, 'enable_second_stamp', [
            'group' => 'General',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Enable second stamp',
            'input' => 'boolean',
            'class' => '',
            'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => 1,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );
    }
}
