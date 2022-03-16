<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Setup\Patch\Schema;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddCmsPageUniqueKey implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    public function __construct(SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    public function apply()
    {
        $this->schemaSetup->startSetup();
        $connection = $this->schemaSetup->getConnection();

        $connection->addIndex(
            $this->schemaSetup->getTable(\Dotsquares\Shopby\Api\CmsPageRepositoryInterface::TABLE),
            $connection->getIndexName(
                \Dotsquares\Shopby\Api\CmsPageRepositoryInterface::TABLE,
                'page_id',
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            'page_id',
            AdapterInterface::INDEX_TYPE_UNIQUE
        );

        $this->schemaSetup->endSetup();

        return $this;
    }

    public static function getDependencies()
    {
        return [\Dotsquares\Shopby\Setup\Patch\Data\RemoveCmsDuplicates::class];
    }

    public function getAliases()
    {
        return [];
    }
}
