<?php

namespace Dotsquares\ShopbySeo\Model\Customizer\Category;

use Magento\Catalog\Model\Category;

class SeoLast implements \Dotsquares\ShopbyBase\Model\Customizer\Category\CustomizerInterface
{
    /**
     * @var \Dotsquares\Shopby\Model\Request
     */
    private $dsshopbyRequest;

    /**
     * @var \Dotsquares\ShopbySeo\Helper\Data
     */
    private $config;

    /**
     * @var \Dotsquares\ShopbyBase\Helper\Meta
     */
    private $metaHelper;

    public function __construct(
        \Dotsquares\Shopby\Model\Request $dsshopbyRequest,
        \Dotsquares\ShopbySeo\Helper\Data $config,
        \Dotsquares\ShopbyBase\Helper\Meta $metaHelper
    ) {
        $this->dsshopbyRequest = $dsshopbyRequest;
        $this->config = $config;
        $this->metaHelper = $metaHelper;
    }

    /**
     * @param Category $category
     */
    public function prepareData(Category $category)
    {
        $page = $this->dsshopbyRequest->getParam('p');
        $limit = $this->dsshopbyRequest->getParam('product_list_limit');
        if ($page && $limit !== 'all') {
            $pageMeta = __(' | Page %1', $page);
            $metaTitle = $this->metaHelper->getOriginPageMetaTitle($category) ?: $category->getName();
            $metaDescription = $this->metaHelper->getOriginPageMetaDescription($category);

            if ($this->config->isAddPageToMetaTitleEnabled() && $metaTitle) {
                $category->setMetaTitle($metaTitle . $pageMeta);
            }

            if ($this->config->isAddPageToMetaDescriprionEnabled() && $metaDescription) {
                $category->setMetaDescription($metaDescription . $pageMeta);
            }
        }
    }
}
