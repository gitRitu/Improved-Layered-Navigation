<?php

namespace Dotsquares\Shopby\Plugin\Theme\Block\Html\Header;

class Logo
{
    public const SHOPBY_ROUTE_NAME = 'dsshopby';
    public const BRAND_ROUTE_NAME = 'dsbrand';

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    public function __construct(\Magento\Framework\App\Request\Http $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Magento\Theme\Block\Html\Header\Logo $subject
     * @param \Closure $closure
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormatParameter)
     */
    public function aroundIsHomePage(\Magento\Theme\Block\Html\Header\Logo $subject, \Closure $closure)
    {
        if (in_array($this->request->getRouteName(), [self::SHOPBY_ROUTE_NAME, self::BRAND_ROUTE_NAME])) {
            return false;
        }
        return $closure();
    }
}
