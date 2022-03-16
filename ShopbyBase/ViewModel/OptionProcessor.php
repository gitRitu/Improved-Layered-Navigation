<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\ViewModel;

use Dotsquares\ShopbyBase\Api\UrlBuilderInterface;
use Dotsquares\ShopbyBase\Helper\FilterSetting;
use Dotsquares\ShopbyBase\Model\AllProductsConfig;
use Dotsquares\ShopbyBase\Model\OptionSetting;

class OptionProcessor implements OptionProcessorInterface
{
    /**
     * @var UrlBuilderInterface
     */
    private $urlBuilder;

    /**
     * @var AllProductsConfig
     */
    private $allProductsConfig;

    public function __construct(UrlBuilderInterface $urlBuilder, AllProductsConfig $allProductsConfig)
    {
        $this->urlBuilder = $urlBuilder;
        $this->allProductsConfig = $allProductsConfig;
    }

    public function process(OptionSetting $setting): array
    {
        $label = $setting->getAttributeOption()->getLabel();
        $title = $label ?: $setting->getTitle();

        return [
            self::IMAGE_URL => $setting->getSliderImageUrl(),
            self::LINK_URL => $this->getOptionSettingUrl($setting),
            self::TITLE => $title,
            OptionSetting::SMALL_IMAGE_ALT => $setting->getSmallImageAlt()
        ];
    }

    /**
     * @param OptionSetting $setting
     * @return string
     */
    private function getOptionSettingUrl(OptionSetting $setting): string
    {
        $filterCode = $setting->getFilterCode();
        if (!$filterCode) {
            return $this->urlBuilder->getBaseUrl();
        }

        if (!$this->allProductsConfig->isAllProductsAvailable()) {
            return '#';
        }

        $attrCode = $setting->getFilterCode();
        if (strpos($attrCode, FilterSetting::ATTR_PREFIX) !== false) {
            $attrCode = substr($attrCode, 5);
        }

        $value = $setting->getOptionId() ?: $setting->getValue();

        return $this->urlBuilder->getUrl(
            'dsshopby/index/index',
            [
                '_query' => [$attrCode => $value],
            ]
        );
    }
}
