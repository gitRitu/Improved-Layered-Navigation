<?php

declare(strict_types=1);

namespace Dotsquares\ShopbySeo\Model;

use Dotsquares\Base\Model\ConfigProviderAbstract;

class ConfigProvider extends ConfigProviderAbstract
{
    public const DSSHOPBY_SEO_REL_NOFOLLOW = 'robots/rel_nofollow';

    /**
     * @var string
     */
    protected $pathPrefix = 'dotsquares_shopby_seo/';

    public function isEnableRelNofollow(): bool
    {
        return (bool) $this->getValue(self::DSSHOPBY_SEO_REL_NOFOLLOW);
    }
}
