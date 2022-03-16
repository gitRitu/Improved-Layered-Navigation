<?php

namespace Dotsquares\Shopby\Model\Source\Attribute;

class Extended extends \Dotsquares\Shopby\Model\Source\Attribute
{
    public const ALL = 'dsshopby_all_attributes';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray($boolean = 1)
    {
        $allOption = [[
            'value' => self::ALL,
            'label' => (string)(__('All Attributes'))
        ]];
        return array_merge($allOption, parent::toOptionArray());
    }
}
