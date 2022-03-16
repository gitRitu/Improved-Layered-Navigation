<?php

namespace Dotsquares\ShopbyPage\Plugin\Catalog\Helper;

use Magento\Catalog\Helper\Category as CategoryHelper;
use Dotsquares\ShopbyPage\Model\Page;

class Category
{
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    public function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
        $this->layerResolver = $layerResolver;
    }

    /**
     * @return \Magento\Catalog\Model\Category|null
     */
    private function getCurrentCategory()
    {
        $catalogLayer = $this->layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

    /**
     * @param CategoryHelper $category
     * @param $canUse
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormatParameter)
     */
    public function afterCanUseCanonicalTag(CategoryHelper $category, $canUse)
    {
        $currentCategory = $this->getCurrentCategory();

        if (!$canUse && $currentCategory !== null) {
            if ($currentCategory->getData(Page::CATEGORY_FORCE_USE_CANONICAL)) {
                $canUse = true;
            }
        }

        return $canUse;
    }
}
