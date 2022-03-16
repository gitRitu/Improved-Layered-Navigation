<?php

namespace Dotsquares\ShopbyPage\Api;

interface PageRepositoryInterface
{
    /**
     * @param \Dotsquares\ShopbyPage\Api\Data\PageInterface $pageData
     * @return \Dotsquares\ShopbyPage\Api\Data\PageInterface
     */
    public function save(\Dotsquares\ShopbyPage\Api\Data\PageInterface $pageData);

    /**
     * @param int $id
     * @return \Dotsquares\ShopbyPage\Api\Data\PageInterface
     */
    public function get($id);

    /**
     * @param int $categoryId
     * @param int $storeId
     *
     * @return \Dotsquares\ShopbyPage\Api\Data\PageSearchResultsInterface
     */
    public function getList($categoryId, $storeId);

    /**
     * @param \Dotsquares\ShopbyPage\Api\Data\PageInterface $pageData
     * @return bool
     */
    public function delete(\Dotsquares\ShopbyPage\Api\Data\PageInterface $pageData);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById($id);
}
