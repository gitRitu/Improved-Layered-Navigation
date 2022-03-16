<?php

namespace Dotsquares\Shopby\Block\Navigation\Widget;

interface WidgetInterface
{
    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     *
     * @return mixed
     */
    public function setFilterSetting(\Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting);

    /**
     * @return mixed
     */
    public function getFilterSetting();
}
