<?php

declare(strict_types=1);

namespace Dotsquares\ShopbySeo\Model\Config\Backend;

use Dotsquares\ShopbySeo\Model\SeoOptions;

class InvalidateSeoCache extends \Magento\Framework\App\Config\Value
{
    /**
     * Processing object after save data
     *
     * @return \Magento\Framework\App\Config\Value
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->invalidate(SeoOptions::CACHE_KEY);
        }

        return parent::afterSave();
    }
}
