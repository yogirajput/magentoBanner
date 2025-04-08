<?php

namespace Yogesh\Banner\Setup\Patch\Schema;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class UpdateColumn implements SchemaPatchInterface
{
    private $moduleDataSetup;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }


    public static function getDependencies()
    {
        return [];
    }


    public function getAliases()
    {
        return [];
    }


    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $tableName = $this->moduleDataSetup->getTable('banner');
        if ($connection->isTableExists($tableName) == true) {
            $connection->modifyColumn(
                $tableName,
                'title',
                ['type' => Table::TYPE_TEXT, 'length' => '2M', 'nullable' => true, 'default' => null],
                'Banner Title'
            );
        }
        $this->moduleDataSetup->endSetup();
    }
}