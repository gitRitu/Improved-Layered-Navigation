<?php

namespace Dotsquares\Shopby\Plugin\Catalog\Block\Product\ProductList;

class Toolbar
{
    /**
     * @var \Dotsquares\ShopbyBase\Model\UrlBuilder
     */
    private $urlBuilder;

    public function __construct(\Dotsquares\ShopbyBase\Model\UrlBuilder $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @param array $params
     * @return mixed
     */
    public function aroundGetPagerUrl($subject, callable $proceed, $params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = false;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = $params;
        return $this->urlBuilder->getUrl('*/*/*', $urlParams);
    }
}
