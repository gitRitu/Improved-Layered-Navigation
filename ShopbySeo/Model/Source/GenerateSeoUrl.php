<?php

declare(strict_types=1);

namespace Dotsquares\ShopbySeo\Model\Source;

class GenerateSeoUrl implements \Magento\Framework\Data\OptionSourceInterface
{
    public const USE_DEFAULT = 0;
    public const YES = 1;
    public const NO = 2;

    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::NO,
                'label' => __('No')
            ],
            [
                'value' => self::YES,
                'label' => __('Yes')
            ],
            [
                'value' => self::USE_DEFAULT,
                'label' => __('Use Default')
            ],
        ];
    }
}
