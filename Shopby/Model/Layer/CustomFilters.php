<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Model\Layer;

use Dotsquares\Shopby\Model\ConfigProvider;

class CustomFilters
{
    public const STOCK = 'stock';
    public const RATING = 'rating';
    public const DS_IS_NEW = 'ds_is_new';
    public const DS_ON_SALE = 'ds_on_sale';

    public const CUSTOM_FILTER_CODES = [
        self::STOCK,
        self::RATING,
        self::DS_IS_NEW,
        self::DS_ON_SALE
    ];

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    public function isCustomFilter(string $attributeCode): bool
    {
        return in_array($attributeCode, self::CUSTOM_FILTER_CODES);
    }

    public function getConfig(string $attributeCode): array
    {
        switch ($attributeCode) {
            case self::STOCK:
                $config = $this->configProvider->getStockConfig();
                break;
            case self::RATING:
                $config = $this->configProvider->getRatingConfig();
                break;
            case self::AM_IS_NEW:
                $config = $this->configProvider->getNewConfig();
                break;
            case self::AM_ON_SALE:
                $config = $this->configProvider->getOnSaleConfig();
                break;
        }

        return $config;
    }
}
