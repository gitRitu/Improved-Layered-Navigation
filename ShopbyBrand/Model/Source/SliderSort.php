<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBrand\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SliderSort implements OptionSourceInterface
{
    public const NAME = 'name';
    public const POSITION = 'position';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::NAME, 'label' => __('Name')],
            ['value' => self::POSITION, 'label' => __('Position')]
        ];
    }

    public function toArray(): array
    {
        return [self::NAME => __('Name'), self::POSITION => __('Position')];
    }
}
