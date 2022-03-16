<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FilterSettingSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface[]
     */
    public function getItems();

    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
