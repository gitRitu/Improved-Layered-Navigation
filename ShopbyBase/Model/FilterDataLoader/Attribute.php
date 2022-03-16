<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Model\FilterDataLoader;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting as FilterSettingResource;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;

class Attribute implements AdapterInterface
{
    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var FilterSettingResource
     */
    private $resource;

    public function __construct(
        Config $eavConfig,
        FilterSettingResource $resource
    ) {
        $this->eavConfig = $eavConfig;
        $this->resource = $resource;
    }

    public function load(FilterSettingInterface $filterSetting, string $filterCode, ?string $fieldName = null): void
    {
        $this->resource->load($filterSetting, $filterCode, $fieldName);
    }

    public function isApplicable(string $filterCode): bool
    {
        $attribute = $this->eavConfig->getAttribute(Product::ENTITY, $filterCode);

        return (bool) $attribute->getAttributeId();
    }
}
