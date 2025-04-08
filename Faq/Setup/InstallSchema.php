<?php

namespace Yogesh\Faq\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        if (!$installer->tableExists('faq')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('faq')
            )->addColumn(
                    'faq_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'faq ID'
                )->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Title'
                )->addColumn(
                    'groupname',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false],
                    'Group name'
                )->addColumn(
					'content',
					Table::TYPE_TEXT,
					'2M',
					[],
					'Faq full Content'
                )->addColumn(
                    'sortorder',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )->addColumn(
                    'is_active',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '1'],
                    'Active Status'
                )->setComment(
                    'Faq Table'
                );
            $installer->getConnection()->createTable($table);
        }
		
		/**
         * Create table 'magefan_blog_post_store'
         */
		if (!$installer->tableExists('faqgroup')) {
			$table = $installer->getConnection()->newTable(
			$installer->getTable('faqgroup')
				)->addColumn(
					'id',
					Table::TYPE_INTEGER,
					null,
					['identity' => true, 'nullable' => false, 'primary' => true],
					'group ID'
				)->addColumn(
					'title',
					Table::TYPE_TEXT,
					50,
					['nullable' => false],
					'Title'
				)->setComment(
					'Faqgroup Table'
				);
			$installer->getConnection()->createTable($table);
		}
		
        $installer->endSetup();

    }
}