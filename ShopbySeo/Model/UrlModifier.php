<?php

namespace Dotsquares\ShopbySeo\Model;

class UrlModifier implements \Dotsquares\ShopbyBase\Api\UrlModifierInterface
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
     * @param string $url
     * @return string
     */
    public function modifyUrl($url)
    {
        return $this->urlHelper->seofyUrl($url);
    }
}
