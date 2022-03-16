<?php

namespace Dotsquares\ShopbySeo\Plugin\XmlSitemap\ShopbyBase\Model;

class Sitemap
{
    /**
     * @var \Dotsquares\ShopbySeo\Helper\Url
     */
    private $helperUrl;

    public function __construct(
        \Dotsquares\ShopbySeo\Helper\Url $helperUrl
    ) {
        $this->helperUrl = $helperUrl;
    }

    /**
     * @param $subject
     * @param $url
     * @return string
     */
    public function afterApplySeoUrl($subject, $url)
    {
        if ($this->helperUrl->isSeoUrlEnabled()) {
            $url = $this->helperUrl->seofyUrl($url);
        }

        return $url;
    }
}
