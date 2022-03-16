<?php

namespace Dotsquares\ShopbyBase\Api;

interface UrlModifierInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function modifyUrl($url);
}
