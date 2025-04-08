<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Yogesh\Feature\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface {

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        //START table setup
        $table = $installer->getConnection()->newTable(
                        $installer->getTable('appnova_feature')
                )->addColumn(
                        'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,], 'Entity ID'
                )->addColumn(
                        'title', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 1024, [ 'nullable' => false,], 'Title'
                )->addColumn(
                        'content', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2M', [ 'nullable' => false,], 'Content'
               )->addColumn(
                        'sort_order', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, [ 'nullable' => false, 'default' => '1',], 'Sort Order'
                )->addColumn(
						'image',\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,255,['nullable' => false],'Image'
                )->addColumn(
                        'is_active', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, [ 'nullable' => false, 'default' => '1',], 'Is Active'
                )->addColumn(
                        'creation_time', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, [ 'nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,], 'Creation Time'
                )->addColumn(
                        'update_time', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, [ 'nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE,], 'Modification Time'
                );
       
        $installer->getConnection()->createTable($table);

        //END   table setup

        $installer->endSetup();
    }

}
