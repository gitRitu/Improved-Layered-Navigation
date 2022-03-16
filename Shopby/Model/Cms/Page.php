<?php

namespace Dotsquares\Shopby\Model\Cms;

class Page extends \Magento\Framework\Model\AbstractModel
{
    public const VAR_SETTINGS = 'dsshopby_settings';

    protected function _construct()
    {
        $this->_init(\Dotsquares\Shopby\Model\ResourceModel\Cms\Page::class);
    }
}
