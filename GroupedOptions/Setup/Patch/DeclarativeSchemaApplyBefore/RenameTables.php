<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Setup\Patch\DeclarativeSchemaApplyBefore;

use Dotsquares\GroupedOptions\Api\GroupRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class RenameTables implements SchemaPatchInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var array
     */
    private $tablesForRename = [
        'dotsquares_conf_group_attr_option' => GroupRepositoryInterface::TABLE_OPTIONS,
        'dotsquares_conf_group_attr' => GroupRepositoryInterface::TABLE,
        'dotsquares_dsshopby_group_attr_option' => GroupRepositoryInterface::TABLE_OPTIONS,
        'dotsquares_dsshopby_group_attr' => GroupRepositoryInterface::TABLE,
        'dotsquares_dsshopby_group_attr_value' => GroupRepositoryInterface::TABLE_VALUES
    ];

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();

        $connection->startSetup();
        foreach ($this->tablesForRename as $oldName => $newName) {
            $oldTableName = $this->resourceConnection->getTableName($oldName);
            $newTableName = $this->resourceConnection->getTableName($newName);
            if ($connection->isTableExists($oldTableName)) {
                if ($connection->isTableExists($newTableName)) {
                    $connection->dropTable($newTableName);
                }
                $connection->renameTable($oldTableName, $newTableName);

                // delete foreign keys because type of column can changed;
                // restored with db_schema.
                foreach ($connection->getForeignKeys($newTableName) as $foreignKey) {
                    $connection->dropForeignKey($newTableName, $foreignKey['FK_NAME']);
                }
            }
        }
        $connection->endSetup();

        return $this;
    }
}
