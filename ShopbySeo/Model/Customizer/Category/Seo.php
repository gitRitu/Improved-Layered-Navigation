<?php

namespace Dotsquares\ShopbySeo\Model\Customizer\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product\ProductList\Toolbar;

class Seo implements \Dotsquares\ShopbyBase\Model\Customizer\Category\CustomizerInterface
{
    public const CANONICAL_ROOT_MODE = 'root';
    public const CANONICAL_CATEGORY_MODE = 'category';

    public const CATEGORY_CURRENT = 'category_current';
    public const CATEGORY_PURE = 'category_pure';
    public const CATEGORY_BRAND_FILTER = 'category_brand_filter';
    public const CATEGORY_FIRST_ATTRIBUTE = 'category_first_attribute';
    public const CATEGORY_CUT_OFF_GET = 'category_cut_off_get';

    public const ROOT_CURRENT = 'root_current';
    public const ROOT_PURE = 'root_pure';
    public const ROOT_FIRST_ATTRIBUTE = 'root_first_attribute';
    public const ROOT_CUT_OFF_GET = 'root_cut_off_get';

    public const PAGE_PARAM_NAME = 'p';

    /**
     * @var array
     */
    private $excludedParams = [
        'product_list_mode',
        'product_list_order',
        'product_list_dir',
        'product_list_limit'
    ];

    /**
     * @var \Dotsquares\ShopbySeo\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dotsquares\ShopbyBase\Helper\Data
     */
    private $baseHelper;

    /**
     * @var \Dotsquares\ShopbyBase\Model\Category\Manager
     */
    protected $categoryManager;

    /**
     * @var \Dotsquares\ShopbyBase\Model\UrlBuilder
     */
    protected $url;

    /**
     * @var \Dotsquares\Shopby\Helper\UrlBuilder
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Magento\LayeredNavigation\Block\Navigation
     */
    protected $navigationBlock;

    /**
     * @var \Dotsquares\Shopby\Model\Request
     */
    protected $dsshopbyRequest;

    public function __construct(
        \Dotsquares\ShopbySeo\Helper\Data $helper,
        \Dotsquares\ShopbyBase\Helper\Data $baseHelper,
        \Dotsquares\ShopbyBase\Model\Category\Manager $categoryManager,
        \Dotsquares\ShopbyBase\Model\UrlBuilder $url,
        \Dotsquares\Shopby\Helper\UrlBuilder $urlBuilder,
        \Magento\Framework\View\LayoutInterface $layout,
        \Dotsquares\Shopby\Model\Request $dsshopbyRequest
    ) {
        $this->helper = $helper;
        $this->baseHelper = $baseHelper;
        $this->categoryManager = $categoryManager;
        $this->url = $url;
        $this->urlBuilder = $urlBuilder;
        $this->layout = $layout;
        $this->dsshopbyRequest = $dsshopbyRequest;
    }

    /**
     * @return \Magento\LayeredNavigation\Block\Navigation|null
     */
    protected function getNavigationBlock()
    {
        if ($this->navigationBlock === null) {
            foreach ($this->layout->getAllBlocks() as $block) {
                if ($block instanceof \Magento\LayeredNavigation\Block\Navigation) {
                    $this->navigationBlock = $block;
                    break;
                }
            }
        }

        return $this->navigationBlock;
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategoryUrl(Category $category)
    {
        return $category->getUrl();
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCanonicalMode(Category $category)
    {
        $mode = self::CANONICAL_CATEGORY_MODE;

        if ($this->categoryManager->getRootCategoryId() === $category->getId()) {
            $mode = self::CANONICAL_ROOT_MODE;
        }

        return $mode;
    }

    /**
     * @return string
     */
    public function getRootModeCanonical()
    {
        $canonical = $this->url->getCurrentUrl();

        switch ($this->helper->getCanonicalRoot()) {
            case self::ROOT_CURRENT:
                $canonical = $this->url->getCurrentUrl();
                break;
            case self::ROOT_PURE:
                $canonical = $this->url->getUrl('dsshopby/index/index');
                break;
            case self::ROOT_FIRST_ATTRIBUTE:
                $canonical = $this->getFirstAttributeValueUrl();
                break;
            case self::ROOT_CUT_OFF_GET:
                $canonical = $this->stripGetParams($this->url->getCurrentUrl());
                break;
        }

        if ($canonical === null) {
            $canonical = $this->url->getCurrentUrl();
        }

        $brandPageUrl = $this->getAttributeValueUrl(
            $this->baseHelper->getBrandAttributeCode()
        );

        if ($brandPageUrl) {
            $canonical = $brandPageUrl;
        }

        return $this->prepareCanonicalUrl($canonical);
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategoryModeCanonical(Category $category)
    {
        $canonical = null;

        switch ($this->helper->getCanonicalCategory()) {
            case self::CATEGORY_CURRENT:
                $canonical = $this->url->getCurrentUrl();
                break;
            case self::CATEGORY_PURE:
                $canonical = $this->getCurrentWithoutFilters($category);
                break;
            case self::CATEGORY_BRAND_FILTER:
                $canonical = $this->getAttributeValueUrl(
                    $this->baseHelper->getBrandAttributeCode()
                );
                break;
            case self::CATEGORY_FIRST_ATTRIBUTE:
                $canonical = $this->getFirstAttributeValueUrl();
                break;
            case self::CATEGORY_CUT_OFF_GET:
                $canonical = $this->stripGetParams($this->url->getCurrentUrl());
                break;
        }

        if ($canonical === null) {
            $canonical = $category->getUrl();
        }

        return $this->prepareCanonicalUrl($canonical);
    }

    /**
     * @param $url
     * @return string
     */
    private function prepareCanonicalUrl($url)
    {
        $pos = max(0, strpos($url, '?'));
        if ($pos) {
            $urlParts = explode('?', $url);
            if (isset($urlParts[0])) {
                $url = $urlParts[0];
                if (isset($urlParts[1])) {
                    // @codingStandardsIgnoreLine
                    parse_str($urlParts[1], $params);
                    foreach ($this->excludedParams as $param) {
                        unset($params[$param]);
                    }
                    if (isset($params[self::PAGE_PARAM_NAME]) && $params[self::PAGE_PARAM_NAME] <= 1) {
                        unset($params[self::PAGE_PARAM_NAME]);
                    }
                    if ($params) {
                        $url .= '?' . http_build_query($params);
                    }
                }
            }
        } else {
            $params = $this->dsshopbyRequest->getRequestParams();
            $page = isset($params['p']) ? array_shift($params['p']) : null;
            $page = (int)$page;
            $url .= $page && $page !== 1 ? '?p=' . $page : '';
        }

        return $url;
    }

    /**
     * @param $category
     * @return string|null
     */
    private function getCurrentWithoutFilters($category)
    {
        $params = $this->dsshopbyRequest->getRequestParams();
        $page = isset($params['p']) ? array_shift($params['p']) : null;
        $page = (int)$page;

        return $page && $page !== 1 ? $category->getUrl() . '?p=' . $page : null;
    }

    /**
     * @param $url
     * @return string
     */
    public function stripGetParams($url)
    {
        $pos = max(0, strpos($url, '?'));
        if ($pos) {
            $url = substr($url, 0, $pos);
        }

        return $url;
    }

    /**
     * @param Category $category
     */
    public function prepareData(Category $category)
    {
        $canonical = $this->url->getCurrentUrl();

        switch ($this->getCanonicalMode($category)) {
            case self::CANONICAL_ROOT_MODE:
                $canonical = $this->getRootModeCanonical();
                break;
            case self::CANONICAL_CATEGORY_MODE:
                $canonical = $this->getCategoryModeCanonical($category);
                break;
        }

        $category->setData('url', $canonical);
    }

    /**
     * @return \Magento\Catalog\Model\Layer\Filter\FilterInterface|null
     */
    protected function getFirstAppliedFilter()
    {
        $appliedFilter = null;

        $navigationBlock = $this->getNavigationBlock();

        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    $appliedFilter = $filter;
                    break;
                }
            }
        }

        return $appliedFilter;
    }

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @return mixed
     */
    protected function getAppliedFilterValue(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        return $this->dsshopbyRequest->getParam($filter->getRequestVar());
    }

    /**
     * @return string
     */
    protected function getFirstAttributeValueUrl()
    {
        $url = null;

        $navigationBlock = $this->getNavigationBlock();

        $appliedFilter = null;

        $query = [];
        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    if (!$appliedFilter) {
                        $appliedFilter = $filter;
                    }

                    $query[$filter->getRequestVar()] = null;
                }
            }
        }

        if ($appliedFilter) {
            $query[$appliedFilter->getRequestVar()] = $this->getAppliedFilterValue($appliedFilter);
        }
        $query[Toolbar::ORDER_PARAM_NAME] = null;
        $query[Toolbar::LIMIT_PARAM_NAME] = null;
        $query[Toolbar::MODE_PARAM_NAME] = null;
        $query[Toolbar::DIRECTION_PARAM_NAME] = null;

        $url = $this->url->getUrl(
            '*/*/*',
            ['_current' => true, '_use_rewrite' => true, '_query' => $query]
        );

        return $url;
    }

    /**
     * @param $attributeCode
     * @return string
     */
    protected function getAttributeValueUrl($attributeCode)
    {
        $url = null;

        $navigationBlock = $this->getNavigationBlock();

        $appliedFilter = null;

        $query = [];
        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    if ($filter instanceof \Dotsquares\Shopby\Model\Layer\Filter\Attribute &&
                        $filter->getAttributeModel()->getAttributeCode() === $attributeCode
                    ) {
                        $appliedFilter = $filter;
                    }

                    $query[$filter->getRequestVar()] = null;
                }
            }
        }

        if ($appliedFilter) {
            $query[$appliedFilter->getRequestVar()] = $this->getAppliedFilterValue($appliedFilter);

            $url = $this->url->getUrl(
                '*/*/*',
                ['_current' => true, '_use_rewrite' => true, '_query' => $query]
            );
        }

        return $url;
    }
}
