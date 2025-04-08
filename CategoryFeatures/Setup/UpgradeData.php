<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Yogesh\CategoryFeatures\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;  
use Magento\Eav\Setup\EavSetupFactory;  
use Magento\Framework\Setup\ModuleContextInterface;  
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
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
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.2') < 0
        ) {

            $num = 6;

            for ($i = 1; $i <= $num; $i++) {

                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'appnova_prod_a'.$i.'_title');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'appnova_prod_a'.$i.'_title',
                    [
                        'type' => 'varchar',
                        'label' => '#'.$i.' - Title',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => 1,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'unique' => false,
                        'apply_to' => 'simple,virtual,bundle,downloadable,configurable',
                        'group' => 'Additional information',
                        'sort_order' => $i*10,
                    ]
                );

                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'appnova_prod_a'.$i.'_body');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'appnova_prod_a'.$i.'_body',
                    [
                        'type' => 'text',
                        'label' => '#'.$i.' - Body',
                        'input' => 'textarea',
                        'class' => '',
                        'source' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => 1,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'unique' => false,
                        'apply_to' => 'simple,virtual,bundle,downloadable,configurable',
                        'group' => 'Additional information',
                        'sort_order' => $i*10+1,
                    ]
                );

            }

        }
        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.3') < 0
        ) {

            $num = 12;

            for ($i = 7; $i <= $num; $i++) {

                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'appnova_prod_a'.$i.'_title');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'appnova_prod_a'.$i.'_title',
                    [
                        'type' => 'varchar',
                        'label' => '#'.$i.' - Title',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => 1,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'unique' => false,
                        'apply_to' => 'simple,virtual,bundle,downloadable,configurable',
                        'group' => 'Additional information',
                        'sort_order' => $i*10+60,
                    ]
                );

                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'appnova_prod_a'.$i.'_body');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'appnova_prod_a'.$i.'_body',
                    [
                        'type' => 'text',
                        'label' => '#'.$i.' - Body',
                        'input' => 'textarea',
                        'class' => '',
                        'source' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => 1,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'unique' => false,
                        'apply_to' => 'simple,virtual,bundle,downloadable,configurable',
                        'group' => 'Additional information',
                        'sort_order' => $i*10+61,
                    ]
                );
                
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'appnova_cat_a'.$i.'_body');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY, 'appnova_cat_a'.$i.'_title', [
                        'type' => 'varchar',
                        'label' => '#'.$i.' - Title',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 5,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Additional information',
                    ]
                );

                $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'appnova_cat_a'.$i.'_body');

                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY, 'appnova_cat_a'.$i.'_body', [
                        'type' => 'text',
                        'label' => '#'.$i.' - Body',
                        'input' => 'textarea',
                        'is_wysiwyg_enabled' => false,
                        'required' => false,
                        'sort_order' => 5,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Additional information',
                    ]
                );

            }

        }
    }
}
