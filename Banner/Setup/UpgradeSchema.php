<?php
namespace Yogesh\Banner\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Blog update
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();

        $version = $context->getVersion();
        $connection = $setup->getConnection();
     
		if (version_compare($version, '2.2.0') < 0) {
            $connection->addColumn(
                $setup->getTable('banner'),
                'btn_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Button Title',
                ]
            );
        }if (version_compare($version, '2.3.0') < 0) {
            $connection->addColumn(
                $setup->getTable('banner'),
                'sort',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Sort Order',
                ]
            );
        }
		if (version_compare($version, '2.5.0') < 0) {
            $connection->addColumn(
                $setup->getTable('banner'),
                'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Store Id'
            );
		$connection->addColumn(
                $setup->getTable('banner'),
                'group',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Group Id'
            );
			$connection->addColumn(
                $setup->getTable('banner'),
                'content',
				[
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Content'
				]
            );
        }
		if (version_compare($version, '2.6.2') < 0) {
            $connection->addColumn(
                $setup->getTable('banner'),
                'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Store Id'
            );	
			
			$connection->addColumn(
                $setup->getTable('banner'),
                'imagemobile',
				[
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Image Mobile'
				]
            );
        }
		
		$setup->endSetup();
    }
}
