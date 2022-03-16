<?php

namespace Dotsquares\Shopby\Plugin\CatalogSearch\Model\Indexer\Fulltext\Action;

use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider as MagentoDataProvider;
use Dotsquares\Shopby\Model\CatalogSearch\Indexer\Fulltext\DataProvider as DotsquaresDataProvider;

class DataProvider
{
    /**
     * @var DotsquaresDataProvider
     */
    private $dotsquaresDataProvider;

    public function __construct(DotsquaresDataProvider $dotsquaresDataProvider)
    {
        $this->dotsquaresDataProvider = $dotsquaresDataProvider;
    }

    /**
     * @param MagentoDataProvider $subject
     * @param callable $proceed
     * @param string $storeId
     * @param array $staticFields
     * @param array|null $productIds
     * @param int|string $lastProductId
     * @param int|string $batchSize
     * @return array
     */
    public function aroundGetSearchableProducts(
        MagentoDataProvider $subject,
        callable $proceed,
        $storeId,
        array $staticFields,
        $productIds = null,
        $lastProductId = 0,
        $batchSize = 100
    ): array {
        return $this->dotsquaresDataProvider->getSearchableProducts(
            (int)$storeId,
            $staticFields,
            $productIds,
            (int)$lastProductId,
            (int)$batchSize
        );
    }
}
