<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\DataMapper;

use Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\DataMapperInterface;
use Dotsquares\Shopby\Model\Layer\Filter\Stock as FilterStock;
use Magento\Store\Model\ScopeInterface;

class StockStatus implements DataMapperInterface
{
    public const FIELD_NAME = 'stock_status';
    public const DOCUMENT_FIELD_NAME = 'quantity_and_stock_status';
    public const INDEX_DOCUMENT = 'document';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var array
     */
    private $inStockProductIds = [];

    /**
     * @var array
     */
    private $allStockProductIds = [];

    /**
     * @var \Magento\CatalogInventory\Model\ResourceModel\Stock\Status
     */
    private $stockStatusResource;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\CatalogInventory\Model\ResourceModel\Stock\Status $stockStatusResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stockStatusResource = $stockStatusResource;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $entityId
     * @param array $entityIndexData
     * @param int $storeId
     * @param array $context
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function map($entityId, array $entityIndexData, $storeId, $context = []): array
    {
        $value = isset($context[self::INDEX_DOCUMENT][self::DOCUMENT_FIELD_NAME])
            ? $context[self::INDEX_DOCUMENT][self::DOCUMENT_FIELD_NAME]
            : $this->isProductInStock($entityId, (int)$storeId);
        return [self::FIELD_NAME => $value];
    }

    /**
     * @param int $entityId
     * @param int $storeId
     * @return int
     */
    private function isProductInStock(int $entityId, int $storeId): int
    {
        if (in_array($entityId, $this->getInStockProductIds($storeId))) {
            return FilterStock::FILTER_IN_STOCK;
        } elseif (in_array($entityId, $this->getAllStockProductIds($storeId))) {
            return FilterStock::FILTER_OUT_OF_STOCK;
        }

        return FilterStock::FILTER_DEFAULT;
    }

    /**
     * @param int $storeId
     * @return array
     */
    private function getInStockProductIds($storeId)
    {
        if (!isset($this->inStockProductIds[$storeId])) {
            $collection = $this->productCollectionFactory->create()->addStoreFilter($storeId);
            $this->stockStatusResource->addStockDataToCollection($collection, true);
            $this->inStockProductIds[$storeId] = $collection->getAllIds();
        }

        return $this->inStockProductIds[$storeId];
    }

    /**
     * @param int $storeId
     * @return array
     */
    private function getAllStockProductIds(int $storeId): array
    {
        if (!isset($this->allStockProductIds[$storeId])) {
            $collection = $this->productCollectionFactory->create()->addStoreFilter($storeId);
            $this->stockStatusResource->addStockDataToCollection($collection, false);
            $this->allStockProductIds[$storeId] = $collection->getAllIds();
        }

        return $this->allStockProductIds[$storeId];
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->scopeConfig->isSetFlag('dsshopby/stock_filter/enabled', ScopeInterface::SCOPE_STORE);
    }

    public function getFieldName(): string
    {
        return self::FIELD_NAME;
    }
}