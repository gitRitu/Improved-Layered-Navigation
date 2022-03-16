<?php

namespace Dotsquares\ShopbyBrand\Model\FilterSetting;

use Dotsquares\ShopbyBase\Model\FilterSetting\AttributeConfig\AttributeListProviderInterface;
use Dotsquares\ShopbyBrand\Helper\Data as BrandHelper;

class AttributeListProvider implements AttributeListProviderInterface
{
    /**
     * @var BrandHelper
     */
    private $helper;

    /**
     * AttributeListProvider constructor.
     * @param BrandHelper $helper
     */
    public function __construct(BrandHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Getting list of attribute codes, which can be configured with Dotsquares Attribute Settings
     * @return array
     */
    public function getAttributeList()
    {
        return [$this->helper->getBrandAttributeCode() => true];
    }
}
