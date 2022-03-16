<?php

namespace Dotsquares\ShopbyBase\Model\FilterSetting\AttributeConfig;

interface AttributeListProviderInterface
{
    /**
     * Getting list of attribute codes, which can be configured with Dotsquares Attribute Settings
     * @return array
     */
    public function getAttributeList();
}
