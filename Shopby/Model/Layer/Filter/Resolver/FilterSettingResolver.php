<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Model\Layer\Filter\Resolver;

use Dotsquares\Shopby\Helper\FilterSetting as FilterSettingHelper;
use Dotsquares\Shopby\Model\ConfigProvider;
use Dotsquares\Shopby\Model\Layer\Filter\Decimal;
use Dotsquares\Shopby\Model\Layer\Filter\IsNew;
use Dotsquares\Shopby\Model\Layer\Filter\OnSale;
use Dotsquares\Shopby\Model\Layer\Filter\Price;
use Dotsquares\Shopby\Model\Layer\Filter\Rating;
use Dotsquares\Shopby\Model\Layer\Filter\Stock;
use Dotsquares\Shopby\Model\Source\PositionLabel;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Model\FilterSetting;
use Dotsquares\ShopbyBase\Model\FilterSetting\IsMultiselect;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class FilterSettingResolver
{
    /**
     * @var FilterSettingInterface[]
     */
    private $filterSetting = [];

    /**
     * @var FilterSettingHelper
     */
    private $settingHelper;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var IsMultiselect
     */
    protected $isMultiselect;

    public function __construct(
        FilterSettingHelper $settingHelper,
        ConfigProvider $configProvider,
        IsMultiselect $isMultiselect
    ) {
        $this->settingHelper = $settingHelper;
        $this->configProvider = $configProvider;
        $this->isMultiselect = $isMultiselect;
    }

    public function isMultiselectAllowed(FilterInterface $filter): bool
    {
        switch (true) {
            case $filter instanceof Stock:
            case $filter instanceof Rating:
            case $filter instanceof OnSale:
            case $filter instanceof IsNew:
                $isMultiselectAllowed = false;
                break;
            case $filter instanceof Price:
            case $filter instanceof Decimal:
                $isMultiselectAllowed = true;
                break;
            default:
                $isMultiselectAllowed = $this->isMultiSelect($filter);
        }

        return $isMultiselectAllowed;
    }

    public function getFilterSetting(FilterInterface $filter): FilterSettingInterface
    {
        if (!isset($this->filterSetting[$filter->getRequestVar()])) {
            $this->filterSetting[$filter->getRequestVar()] = $this->settingHelper->getSettingByLayerFilter($filter);
        }

        return $this->filterSetting[$filter->getRequestVar()];
    }

    public function isSingleChoiceMode(): bool
    {
        return $this->configProvider->isSingleChoiceMode();
    }

    private function isMultiSelect(FilterInterface $filter): bool
    {
        $filterSetting = $this->getFilterSetting($filter);

        return $this->isMultiselect->execute(
            $filterSetting->getAttributeCode(),
            $filterSetting->isMultiselect(),
            $filterSetting->getDisplayMode()
        );
    }
}
