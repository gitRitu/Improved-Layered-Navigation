<?php

namespace Dotsquares\ShopbyPage\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Dotsquares\ShopbyPage\Api\Data\PageInterface[]
     */
    public function getItems();

    /**
     * @param \Dotsquares\ShopbyPage\Api\Data\PageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
