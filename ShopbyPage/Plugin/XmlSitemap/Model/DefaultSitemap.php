<?php

namespace Dotsquares\ShopbyPage\Plugin\XmlSitemap\Model;

class DefaultSitemap
{
    /**
     * @var \Magento\Sitemap\Helper\Data
     */
    private $helper;

    /**
     * @var \Dotsquares\ShopbyPage\Model\ResourceModel\Page\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Url
     */
    private $url;

    public function __construct(
        \Dotsquares\ShopbyPage\Model\ResourceModel\Page\CollectionFactory $collectionFactory,
        \Magento\Sitemap\Helper\Data $helper,
        \Magento\Framework\Url $url
    ) {
        $this->helper = $helper;
        $this->collectionFactory = $collectionFactory;
        $this->url = $url;
    }

    /**
     * @param \Magento\Sitemap\Model\Sitemap $subject
     * @return \Dotsquares\ShopbyPage\Model\ResourceModel\Page\Collection
     */
    public function afterCollectSitemapItems(\Magento\Sitemap\Model\Sitemap $subject)
    {
        $subject->addSitemapItem(new \Magento\Framework\DataObject(
            [
                'changefreq' => $this->helper->getPageChangefreq($subject->getStoreId()),
                'priority' => $this->helper->getPagePriority($subject->getStoreId()),
                'collection' => $this->loadPageCollection($subject->getStoreId())
            ]
        ));
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
