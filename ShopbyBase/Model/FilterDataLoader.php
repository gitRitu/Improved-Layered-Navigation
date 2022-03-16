<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Model;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Model\FilterDataLoader\AdapterInterface;
use Dotsquares\ShopbyBase\Model\FilterDataLoader\FilterDataLoaderInterface;

class FilterDataLoader
{
    /**
     * @var array
     */
    private $adapters = [];

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    /**
     * Method loads custom filters data and writes it to FilterSetting model
     *
     * @param FilterSettingInterface $filterSetting
     * @param string $filterCode
     * @param string $fieldName
     */
    public function load(FilterSettingInterface $filterSetting, string $filterCode, ?string $fieldName = null): void
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof AdapterInterface
                && $adapter->isApplicable($filterCode)
            ) {
                $adapter->load($filterSetting, $filterCode, $fieldName);
                break;
            }
        }
    }
}
