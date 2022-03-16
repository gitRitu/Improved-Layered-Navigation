<?php

namespace Dotsquares\ShopbyPage\Model;

use Magento\Framework\Model\AbstractExtensibleModel;

class Page extends AbstractExtensibleModel
{
    /**
     * Position of placing meta data in category
     */
    public const POSITION_REPLACE = 'replace';
    public const POSITION_AFTER = 'after';
    public const POSITION_BEFORE = 'before';

    public const CATEGORY_FORCE_USE_CANONICAL = 'dsshopby_page_force_use_canonical';
    public const MATCHED_PAGE = 'dsshopby_matched_page';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dotsquares\ShopbyPage\Model\ResourceModel\Page::class);
    }
}
