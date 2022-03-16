<?php

namespace Dotsquares\ShopbyBrand\Ui\Component\Listing\Columns;

class SliderImage extends Image
{
    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface $brand
     * @return null|string
     */
    protected function getImage(\Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface $brand)
    {
        return $brand->getSliderImageUrl()
            ? $brand->getSliderImageUrl()
            : $this->imageHelper->getDefaultPlaceholderUrl();
    }
}
