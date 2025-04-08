<?php
namespace Yogesh\Categorybanner\Setup;
 
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
 
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory.
     *
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;
    protected $categorySetupFactory;
 
    /**
     * Init.
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory, \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory)
    {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }
 
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
        $setup = $this->categorySetupFactory->create(['setup' => $setup]);

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'custom_category_banner_id');

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_category_banner_id',
            [
                'type' => 'varchar',
                'label' => 'Category banner to be used for product',
                'input' => 'select',
                //'input' => 'text',
                'required' => false,
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'source' => 'Yogesh\Categorybanner\Model\Source\Banners',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => 'simple,virtual,bundle,downloadable,configurable',
                'group' => 'General',
            ]
        );

        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_background_color', [
                'type' => 'varchar',
                'label' => 'Custom background color',
                'input' => 'text',
                'required' => false,
                'sort_order' => 5,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        );
        
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_image_1', [
                'type' => 'varchar',
                'label' => 'Custom image #1',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        );   
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_text_1', [
                'type' => 'varchar',
                'label' => 'Custom text #1',
                'input' => 'text',
                'required' => false,
                'sort_order' => 20,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_link_1', [
                'type' => 'varchar',
                'label' => 'Custom link #1',
                'input' => 'text',
                'required' => false,
                'sort_order' => 25,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_image_2', [
                'type' => 'varchar',
                'label' => 'Custom image #2',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 30,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_text_2', [
                'type' => 'varchar',
                'label' => 'Custom text #2',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_link_2', [
                'type' => 'varchar',
                'label' => 'Custom link #2',
                'input' => 'text',
                'required' => false,
                'sort_order' => 45,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_image_3', [
                'type' => 'varchar',
                'label' => 'Custom image #3',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        );
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_text_3', [
                'type' => 'varchar',
                'label' => 'Custom text #3',
                'input' => 'text',
                'required' => false,
                'sort_order' => 60,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_link_3', [
                'type' => 'varchar',
                'label' => 'Custom link #3',
                'input' => 'text',
                'required' => false,
                'sort_order' => 65,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_image_4', [
                'type' => 'varchar',
                'label' => 'Custom image #4',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 70,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        );
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_text_4', [
                'type' => 'varchar',
                'label' => 'Custom text #4',
                'input' => 'text',
                'required' => false,
                'sort_order' => 80,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 
        $setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'custom_link_4', [
                'type' => 'varchar',
                'label' => 'Custom link #4',
                'input' => 'text',
                'required' => false,
                'sort_order' => 85,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Top bar',
            ]
        ); 

        /*
		$eavSetup->addAttribute(
		    \Magento\Catalog\Model\Category::ENTITY,
		    'topbar_1',
		    [
		        'type' => 'text',
		        'label' => 'Block #1',
		        'input' => 'textarea',
		        'required' => false,
		        'sort_order' => 1,
		        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
		        'wysiwyg_enabled' => true,
		        'is_html_allowed_on_front' => true,
		        'group' => 'Top bar',
		    ]
		);

		$eavSetup->addAttribute(
		    \Magento\Catalog\Model\Category::ENTITY,
		    'topbar_2',
		    [
		        'type' => 'text',
		        'label' => 'Block #2',
		        'input' => 'textarea',
		        'required' => false,
		        'sort_order' => 2,
		        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
		        'wysiwyg_enabled' => true,
		        'is_html_allowed_on_front' => true,
		        'group' => 'Top bar',
		    ]
		);

		$eavSetup->addAttribute(
		    \Magento\Catalog\Model\Category::ENTITY,
		    'topbar_3',
		    [
		        'type' => 'text',
		        'label' => 'Block #3',
		        'input' => 'textarea',
		        'required' => false,
		        'sort_order' => 4,
		        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
		        'wysiwyg_enabled' => true,
		        'is_html_allowed_on_front' => true,
		        'group' => 'Top bar',
		    ]
		);

		$eavSetup->addAttribute(
		    \Magento\Catalog\Model\Category::ENTITY,
		    'topbar_4',
		    [
		        'type' => 'text',
		        'label' => 'Block #4',
		        'input' => 'textarea',
		        'required' => false,
		        'sort_order' => 4,
		        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
		        'wysiwyg_enabled' => true,
		        'is_html_allowed_on_front' => true,
		        'group' => 'Top bar',
		    ]
		);
        */
    }
}
