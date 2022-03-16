<?php

namespace Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting;

/**
 * FilterSetting Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Collection protected constructor
     */
    protected function _construct()
    {
        $this->_init(
            \Dotsquares\ShopbyBase\Model\FilterSetting::class,
            \Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting::class
        );
    }
}
