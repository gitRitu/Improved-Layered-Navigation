<?php

namespace Dotsquares\Shopby\Plugin\Catalog\Model\ResourceModel\Product\Attribute;

use \Dotsquares\Shopby\Model\Search\RequestGenerator;

class Collection
{
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $subject
     * @param \Closure $closure
     * @param $column
     * @param $value
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormatParameter)
     */
    public function aroundGetItemByColumnValue(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $subject,
        \Closure $closure,
        $column,
        $value
    ) {
        if ($column == 'attribute_code'
            && ($pos = strpos($value, RequestGenerator::FAKE_SUFFIX)) !== false
        ) {
            $value = substr($value, 0, $pos);
        }
        return $closure($column, $value);
    }
}
