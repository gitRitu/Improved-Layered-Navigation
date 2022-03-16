<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Plugin\Catalog\Model\ResourceModel\Product\Indexer\Eav\AbstractEav;

use Dotsquares\Shopby\Model\ConfigProvider;
use Dotsquares\Shopby\Model\ResourceModel\Catalog\Product\Indexer\Eav\DeleteOutOfStockChild;
use Magento\Catalog\Model\ResourceModel\Product\Indexer\Eav\AbstractEav;

class ExcludeOutOfStock
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var DeleteOutOfStockChild
     */
    private $deleteOutOfStockChild;

    public function __construct(
        ConfigProvider $configProvider,
        DeleteOutOfStockChild $deleteOutOfStockChild
    ) {
        $this->configProvider = $configProvider;
        $this->deleteOutOfStockChild = $deleteOutOfStockChild;
    }

    public function afterReindexEntities(
        AbstractEav $subject
    ): AbstractEav {
        if ($this->configProvider->isExcludeOutOfStock()) {
            $this->deleteOutOfStockChild->execute($subject);
        }

        return $subject;
    }
}
