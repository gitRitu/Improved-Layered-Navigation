<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Model;

class ConfigProvider extends \Dotsquares\Base\Model\ConfigProviderAbstract
{
    public const DSSHOPBY_ROOT_GENERAL_URL_PATH = 'general/url';
    public const DSSHOPBY_ROOT_ENABLED_PATH = 'general/enabled';

    /**
     * @var string
     */
    protected $pathPrefix = 'dsshopby_root/';

    /**
     * @return string
     */
    public function getAllProductsUrlKey()
    {
        return $this->getValue(self::DSSHOPBY_ROOT_GENERAL_URL_PATH);
    }

    /**
     * @return bool
     */
    public function isAllProductsEnabled(): bool
    {
        return $this->isSetFlag(self::DSSHOPBY_ROOT_ENABLED_PATH);
    }
}
