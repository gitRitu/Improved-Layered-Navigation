<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyPage\Model\Sitemap\ItemProvider;

use Dotsquares\ShopbyBase\Model\SitemapBuilder;
use Dotsquares\ShopbyPage\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\Url;

class CustomPage
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var SitemapBuilder
     */
    private $sitemapBuilder;

    public function __construct(
        CollectionFactory $collectionFactory,
        Url $url,
        SitemapBuilder $sitemapBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->url = $url;
        $this->sitemapBuilder = $sitemapBuilder;
    }

    /**
     * @param int $storeId
     * @return array|\Magento\Sitemap\Model\SitemapItemInterface[]
     */
    public function getItems($storeId)
    {
        $pages = $this->loadPageCollection($storeId)->getItems();

        return $this->sitemapBuilder->prepareItems($pages, $storeId);
    }

    /**
     * @param int $storeId
     *
     * @return \Dotsquares\ShopbyPage\Model\ResourceModel\Page\Collection
     */
    private function loadPageCollection($storeId)
    {
        /** @var \Dotsquares\ShopbyPage\Model\ResourceModel\Page\Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('url', ['neq' => ''])
            ->addStoreFilter($storeId);

        foreach ($collection as &$page) {
            if (strpos($page->getUrl(), $this->url->getBaseUrl()) !== false) {
                $page->setUrl(str_replace($this->url->getBaseUrl(), '', $page->getUrl()));
            }
        }

        return $collection;
    }
}
