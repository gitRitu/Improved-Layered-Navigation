<?php

namespace Dotsquares\ShopbyPage\Plugin\XmlSitemap\Model;

use Dotsquares\ShopbyPage\Model\ResourceModel\Page\CollectionFactory;
//use Amasty\XmlSitemap\Model\Sitemap as NativeSitemap;
use Magento\Framework\UrlInterface;

class Sitemap
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->urlBuilder = $urlBuilder;
    }

     /*   * @param NativeSitemap $subject*/
    /**
     * @param \Closure $proceed
     * @param $storeId
     *
     * @return \Dotsquares\ShopbyPage\Model\ResourceModel\Page\Collection
     */
    public function aroundGetShopByPageCollection(\Closure $proceed, $storeId)
    {
        /** @var \Dotsquares\ShopbyPage\Model\ResourceModel\Page\Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('url', ['neq' => ''])
            ->addStoreFilter($storeId);

        foreach ($collection as &$page) {
            if ($page->getUrl() && strpos($page->getUrl(), 'http') === false) {
                $page->setUrl($this->urlBuilder->getBaseUrl() . ltrim($page->getUrl(), '/'));
            }
        }

        return $collection;
    }
}
