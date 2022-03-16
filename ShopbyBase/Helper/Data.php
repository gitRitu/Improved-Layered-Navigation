<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Helper;

use Dotsquares\ShopbyBase\Model\Integration\DummyObject;
use Dotsquares\ShopbySeo\Model\ConfigProvider;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Dotsquares\ShopbyBase\Model\Integration\IntegrationFactory;

class Data extends AbstractHelper
{
    public const SHOPBY_MODULE_NAME = 'Dotsquares_Shopby';

    public const SHOPBY_CATEGORY_INDEX = 'dotsquares_shopby_category_index';

    public const SHOPBY_SEO_PARSED_PARAMS = 'dotsquares_shopby_seo_parsed_params';

    public const SHOPBY_BRAND_POPUP = 'shopby_brand_popup';

    public const SHOPBY_SWITCHER_STORE_ID = 'shopby_switcher_store_id';

    /**
     * @var \Magento\Framework\Module\ModuleList
     */
    private $moduleList;

    /**
     * @var \Magento\Framework\Module\ModuleResource
     */
    private $moduleResource;

    /**
     * @var IntegrationFactory
     */
    private $integrationFactory;

    /**
     * @var \Zend_Http_UserAgent
     */
    private $userAgent;

    public function __construct(
        Context $context,
        \Magento\Framework\Module\ModuleList $moduleList,
        \Magento\Framework\Module\ModuleResource $moduleResource,
        IntegrationFactory $integrationFactory,
        \Zend_Http_UserAgent $userAgent
    ) {
        parent::__construct($context);
        $this->moduleList = $moduleList;
        $this->moduleResource = $moduleResource;
        $this->integrationFactory = $integrationFactory;
        $this->userAgent = $userAgent;
    }

    /**
     * @return null
     */
    public function getShopbyVersion()
    {
        return $this->moduleResource->getDbVersion(self::SHOPBY_MODULE_NAME);
    }

    /**
     * @return bool
     */
    public function isShopbyInstalled()
    {
        return ($this->moduleList->getOne(self::SHOPBY_MODULE_NAME) !== null)
            && $this->getShopbyVersion();
    }

    /**
     * @return string
     */
    public function getBrandAttributeCode()
    {
        /** @var \Dotsquares\ShopbyBrand\Helper\Data|DummyObject $brandHelper */
        $brandHelper = $this->integrationFactory->get(\Dotsquares\ShopbyBrand\Helper\Data::class, true);

        return (string)$brandHelper->getBrandAttributeCode();
    }

    /**
     * @return string
     */
    public function getBrandUrlKey()
    {
        /** @var \Dotsquares\ShopbyBrand\Helper\Data|DummyObject $brandHelper */
        $brandHelper = $this->integrationFactory->get(\Dotsquares\ShopbyBrand\Helper\Data::class, true);

        return (string)$brandHelper->getBrandUrlKey();
    }

    /**
     * @return bool
     */
    public function isAddSuffixToShopby()
    {
        /** @var \Dotsquares\ShopbySeo\Helper\Data|DummyObject $urlHelper */
        $urlHelper = $this->integrationFactory->get(\Dotsquares\ShopbySeo\Helper\Url::class, true);

        return $urlHelper->isAddSuffixToShopby();
    }

    /**
     * @return bool
     */
    public function isEnableRelNofollow()
    {
        /** @var ConfigProvider|DummyObject $seoHelper */
        $seoConfigProvider = $this->integrationFactory->get(ConfigProvider::class, true);

        return $seoConfigProvider->isEnableRelNofollow();
    }
}
