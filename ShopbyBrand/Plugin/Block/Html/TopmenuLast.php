<?php

namespace Dotsquares\ShopbyBrand\Plugin\Block\Html;

use Dotsquares\ShopbyBrand\Model\Source\TopmenuLink as TopmenuSource;

class TopmenuLast extends \Dotsquares\ShopbyBrand\Plugin\Block\Html\Topmenu
{
    /**
     * @return int
     */
    protected function getPosition()
    {
        return TopmenuSource::DISPLAY_LAST;
    }
}
