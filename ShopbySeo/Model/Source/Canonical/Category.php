<?php

namespace Dotsquares\ShopbySeo\Model\Source\Canonical;

use \Dotsquares\ShopbySeo\Model\Customizer\Category\Seo;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $optionValue => $optionLabel) {
            $options[] = [
                'value' => $optionValue,
                'label' => $optionLabel
            ];
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            Seo::CATEGORY_CURRENT => __('Keep current URL'),
            Seo::CATEGORY_PURE => __('URL Without Filters'),
            Seo::CATEGORY_BRAND_FILTER => __('Brand Filter Only'),
            Seo::CATEGORY_FIRST_ATTRIBUTE => __('First Attribute Value'),
            Seo::CATEGORY_CUT_OFF_GET => __('Current URL without Get parameters')
        ];
    }
}
