<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Observer;

use Dotsquares\ShopbyBase\Helper\FilterSetting;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SetCategoryFilterSettingData implements ObserverInterface
{
    private const CATEGORY_ATTRIBUTE_CODE = 'category_ids';

    /**
     * @var FilterSetting
     */
    private $filterSetting;

    public function __construct(
        FilterSetting $filterSetting
    ) {
        $this->filterSetting = $filterSetting;
    }

    public function execute(Observer $observer): void
    {
        $categoryFilterSetting = $observer->getData('object');
        if ($categoryFilterSetting->getAttributeCode() === self::CATEGORY_ATTRIBUTE_CODE) {
            $categoryFilterSetting->addData($this->filterSetting->getCustomDataForCategoryFilter());
        }
    }
}
