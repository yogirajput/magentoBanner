<?php
namespace Yogesh\Multiplesell\Setup;

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
    
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'appnova_multiple_xsell', [
            'type' => 'text',
            'label' => 'Multiple cross-sell products',
            'input' => 'textarea',
            'required' => false,
            'user_defined' => true,
            'visible' => true,
            'sort_order' => 100,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'group' => 'General',
            'apply_to' => 'simple,configurable',
            'note' => 'Add a list of SKUs, one per row. Put the number of items to be added to cart on cross-sell after a pipe "|" at the end of the row',
            ]
        );

    }
}
