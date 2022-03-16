<?php

namespace Dotsquares\ShopbySeo\Helper;

use Dotsquares\Base\Model\Serializer;
use Dotsquares\ShopbySeo\Model\Source\GenerateSeoUrl;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting\CollectionFactory;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class Data extends AbstractHelper
{
    public const CANONICAL_ROOT = 'dotsquares_shopby_seo/canonical/root';
    public const CANONICAL_CATEGORY = 'dotsquares_shopby_seo/canonical/category';
    public const DOTSQUARES_SHOPBY_SEO_URL_SPECIAL_CHAR = 'dotsquares_shopby_seo/url/special_char';
    public const DOTSQUARES_SHOPBY_SEO_URL_ATTRIBUTE_NAME = 'dotsquares_shopby_seo/url/attribute_name';
    public const DOTSQUARES_SHOPBY_SEO_URL_FILTER_WORD = 'dotsquares_shopby_seo/url/filter_word';
    public const DSSHOPBY_ROOT_GENERAL_URL = 'dsshopby_root/general/url';
    public const DSSHOPBY_SEO_PAGE_META_TITLE = 'dotsquares_shopby_seo/other/page_meta_title';
    public const DSSHOPBY_SEO_PAGE_META_DESCR = 'dotsquares_shopby_seo/other/page_meta_descriprion';
    public const SKIP_REQUEST_FLAG = 'shopby_seo_skip_request_flag';
    public const SEO_REDIRECT_FLAG = 'shopby_seo_redirect_flag';
    public const SEO_REDIRECT_MISSED_SUFFIX_FLAG = 'shopby_seo_missed_suffix_redirect_flag';
    public const HAS_PARSED_PARAMS = 'shopby_seo_has_parsed_params_flag';
    public const HAS_ROUTE_PARAMS = 'shopby_seo_has_route_params_flag';
    public const IS_MODULE_ENABLED = 'dotsquares_shopby_seo/url/mode';

    /**
     * @var CollectionFactory
     */
    private $settingCollectionFactory;

    /**
     * @var  StoreManager
     */
    private $storeManager;

    /**
     * @var array|null
     */
    private $seoSignificantAttributeCodes;

    /**
     * @var Config
     */
    private $configHelper;

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var array
     */
    private $skipRequestIdentifiers = [
        'catalog/category/',
        'catalog/product/',
        'cms/page/',
        'dotsquares_xsearch/',
        'customer/',
        'checkout/',
        'catalogsearch'
    ];

    /**
     * @var array
     */
    private $attributeUrlAliases = [];

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        Context $context,
        CollectionFactory $settingCollectionFactory,
        StoreManager $storeManager,
        Config $configHelper,
        UrlFinderInterface $urlFinder,
        Serializer $serializer,
        array $skipRequestIdentifiers = []
    ) {
        parent::__construct($context);
        $this->settingCollectionFactory = $settingCollectionFactory;
        $this->storeManager = $storeManager;
        $this->configHelper = $configHelper;
        $this->urlFinder = $urlFinder;
        $this->skipRequestIdentifiers = array_merge($this->skipRequestIdentifiers, $skipRequestIdentifiers);
        $this->serializer = $serializer;
    }

    public function getSeoSignificantAttributeCodes(): array
    {
        if ($this->seoSignificantAttributeCodes === null) {
            if ($this->configHelper->isSeoUrlEnabled()) {
                $collection = $this->settingCollectionFactory->create();
                $yesValue = $this->configHelper->isGenerateSeoByDefault()
                    ? [GenerateSeoUrl::YES, GenerateSeoUrl::USE_DEFAULT]
                    : [GenerateSeoUrl::YES];
                $collection->addFieldToFilter(FilterSettingInterface::IS_SEO_SIGNIFICANT, $yesValue);
                $aliases = $collection->getColumnValues('attribute_url_alias');
                $attributeCodes = $collection->getColumnValues(FilterSettingInterface::ATTRIBUTE_CODE);
                $this->setAttributeUrlAliases($attributeCodes, $aliases);
            }

            $this->seoSignificantAttributeCodes = $attributeCodes ?? [];
        }

        return $this->seoSignificantAttributeCodes;
    }

    private function setAttributeUrlAliases(array $filterCodes, array $aliases): void
    {
        foreach ($aliases as &$alias) {
            $alias = $this->serializer->unserialize($alias);
        }

        $this->attributeUrlAliases = array_combine($filterCodes, $aliases);
    }

    public function getAttributeUrlAliases(): array
    {
        return $this->attributeUrlAliases;
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function isAttributeSeoSignificant($attribute)
    {
        if ($attribute instanceof \Magento\Eav\Model\Entity\Attribute) {
            $attribute = $attribute->getAttributeCode();
        }
        $codes = $this->getSeoSignificantAttributeCodes();
        return in_array($attribute, $codes);
    }

    /**
     * @return string
     */
    public function getSpecialChar()
    {
        return $this->scopeConfig->getValue(self::DOTSQUARES_SHOPBY_SEO_URL_SPECIAL_CHAR, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getCanonicalRoot()
    {
        return $this->scopeConfig->getValue(self::CANONICAL_ROOT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getCanonicalCategory()
    {
        return $this->scopeConfig->getValue(self::CANONICAL_CATEGORY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getGeneralUrl()
    {
        return $this->scopeConfig->getValue(self::DSSHOPBY_ROOT_GENERAL_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isIncludeAttributeName()
    {
        return $this->scopeConfig->getValue(self::DOTSQUARES_SHOPBY_SEO_URL_ATTRIBUTE_NAME, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getFilterWord()
    {
        return $this->scopeConfig->getValue(self::DOTSQUARES_SHOPBY_SEO_URL_FILTER_WORD, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function isAddPageToMetaTitleEnabled()
    {
        return $this->scopeConfig->getValue(self::DSSHOPBY_SEO_PAGE_META_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function isAddPageToMetaDescriprionEnabled()
    {
        return $this->scopeConfig->getValue(self::DSSHOPBY_SEO_PAGE_META_DESCR, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->_urlBuilder;
    }

    /**
     * @param RequestInterface $request
     * @param bool $allowEmptyModuleName = false
     * @return bool;
     */
    public function isAllowedRequest(RequestInterface $request, $allowEmptyModuleName = false)
    {
        if (!$allowEmptyModuleName && !$request->getModuleName()) {
            return false;
        }

        $identifier = ltrim($request->getOriginalPathInfo(), '/');
        if (!empty($identifier)) {
            $this->skipXsearchIdentifier();
            foreach ($this->skipRequestIdentifiers as $skipRequestIdentifier) {
                if (strpos($identifier, $skipRequestIdentifier) === 0) {
                    return false;
                }
            }

            $rewrite = $this->urlFinder->findOneByData([
                UrlRewrite::REQUEST_PATH => $identifier,
                UrlRewrite::STORE_ID => $this->storeManager->getStore()->getId(),
            ]);
            if ($rewrite !== null) {
                return false;
            }

            return true;
        }

        return false;
    }

    private function skipXsearchIdentifier()
    {
        if ($this->configHelper->isModuleOutputEnabled('Dotsquares_Xsearch')
            && $this->configHelper->getConfig('dotsquares_xsearch/general/enable_seo_url')
        ) {
            $this->skipRequestIdentifiers[] = $this->configHelper->getConfig('dotsquares_xsearch/general/seo_key');
        }
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return !!$this->scopeConfig->getValue(self::IS_MODULE_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
