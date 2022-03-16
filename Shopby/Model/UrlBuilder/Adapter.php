<?php

namespace Dotsquares\Shopby\Model\UrlBuilder;

use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class Adapter implements \Dotsquares\ShopbyBase\Api\UrlBuilder\AdapterInterface
{
    public const SELF_ROUTE_PATH = 'dsshopby/index/index';
    public const SELF_MODULE_NAME = 'dsshopby';
    public const SAME_PAGE_ROUTE = '*/*/*';

    /**
     * @var \Magento\Framework\Url
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        \Magento\Framework\UrlFactory $urlBuilderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopConfig,
        RequestInterface $request
    ) {
        $this->urlBuilder = $urlBuilderFactory->create();
        $this->scopeConfig = $scopConfig;
        $this->request = $request;
    }

    /**
     * @param null $routePath
     * @param null $routeParams
     * @return string|null
     */
    public function getUrl($routePath = null, $routeParams = null)
    {
        $routePath = trim($routePath, '/');
        if ($routePath == self::SELF_ROUTE_PATH
            || ($this->request->getModuleName() == self::SELF_MODULE_NAME && $routePath == self::SAME_PAGE_ROUTE)
        ) {
            $urlKey = $this->scopeConfig->getValue(
                \Dotsquares\Shopby\Helper\Data::DSSHOPBY_ROOT_GENERAL_URL_PATH,
                ScopeInterface::SCOPE_STORE
            );
            if ($urlKey) {
                if (isset($routeParams['_scope'])) {
                    $this->urlBuilder->setScope($routeParams['_scope']);
                } else {
                    $this->urlBuilder->setScope(null);
                }
                $routeParams['_direct'] = $urlKey . $this->getSuffix();
                $routePath = '';
            }
            return $this->urlBuilder->getUrl($routePath, $routeParams);
        }
        return null;
    }

    /**
     * @return null
     */
    public function getSuffix()
    {
        return null;
    }
}
