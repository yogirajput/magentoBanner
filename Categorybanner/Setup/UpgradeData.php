<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Yogesh\Categorybanner\Setup;

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
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'appnova_category_icon');
             /**
             * Add attributes to the eav/attribute
             */

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'appnova_category_icon', [
                    'type' => 'varchar',
                    'label' => 'Category icon',
                    'input' => 'image',
                    'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                    'required' => false,
                    'sort_order' => 10,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Content',
                ]
            );

        }

        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.3') < 0
        ) {
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'appnova_category_icon2');
             /**
             * Add attributes to the eav/attribute
             */

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'appnova_category_icon2', [
                    'type' => 'varchar',
                    'label' => 'Category icon for filters',
                    'input' => 'image',
                    'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                    'required' => false,
                    'sort_order' => 11,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Content',
                ]
            );

        }

        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.4') < 0
        ) {
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'appnova_category_op_title');
             /**
             * Add attributes to the eav/attribute
             */

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'appnova_category_op_title', [
                    'type' => 'varchar',
                    'label' => 'Operations custom title',
                    'input' => 'text',
                    'required' => false,
                    'user_defined' => true,
                    'sort_order' => 5,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Content',
                ]
            );

        }

    }
}
