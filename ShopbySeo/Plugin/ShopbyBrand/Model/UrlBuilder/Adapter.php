<?php

namespace Dotsquares\ShopbySeo\Plugin\ShopbyBrand\Model\UrlBuilder;

class Adapter
{
    /**
     * @var \Dotsquares\ShopbySeo\Helper\Url
     */
    private $urlHelper;

    public function __construct(\Dotsquares\ShopbySeo\Helper\Url $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param $subject
     * @param $result
     * @return string|null
     */
    public function afterGetSuffix($subject, $result)
    {
        if ($this->urlHelper->isAddSuffixToShopby()) {
            return $this->urlHelper->getSeoSuffix();
        }
        return $result;
    }
}
