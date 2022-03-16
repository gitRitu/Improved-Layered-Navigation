<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Helper;

use Dotsquares\ShopbyBase\Model\FilterSetting\FilterResolver;
use Dotsquares\ShopbyBase\Model\FilterSettingFactory;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\App\Helper\Context;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Magento\Store\Model\ScopeInterface;

class FilterSetting extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const ATTR_PREFIX = 'attr_';

    /**
     * @var  FilterSettingFactory
     */
    protected $settingFactory;

    /**
     * @var FilterResolver
     */
    private $filterResolver;

    public function __construct(
        Context $context,
        FilterSettingFactory $settingFactory,
        FilterResolver $filterResolver
    ) {
        parent::__construct($context);
        $this->settingFactory = $settingFactory;
        $this->filterResolver = $filterResolver;
    }

    /**
     * @param FilterInterface $layerFilter
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    public function getSettingByLayerFilter(FilterInterface $layerFilter)
    {
        $filterCode = $this->getFilterCode($layerFilter);
        $setting = $this->getFilterSettingByCode($filterCode);
        if ($setting === null) {
            $setting = $this->settingFactory->create(
                ['data' => [FilterSettingInterface::ATTRIBUTE_CODE => $filterCode]]
            );
        }

        $setting->setAttributeModel($layerFilter->getData('attribute_model'));

        return $setting;
    }

    public function getSettingByAttribute(AttributeInterface $attributeModel): ?FilterSettingInterface
    {
        return $this->filterResolver->getFilterSetting($attributeModel);
    }

    /**
     * @param FilterInterface $layerFilter
     * @return null|string
     */
    protected function getFilterCode(FilterInterface $layerFilter)
    {
        $attribute = $layerFilter->getData('attribute_model');
        if (!$attribute) {
            $categorySetting = $layerFilter->getSetting();

            return is_object($categorySetting) ? $categorySetting->getFilterCode() : null;
        }

        return is_object($attribute) ? $attribute->getAttributeCode() : null;
    }

    /**
     * @param string $filterName
     * @param string $configName
     * @return string
     */
    public function getConfig($filterName, $configName)
    {
        return $this->scopeConfig->getValue(
            'dsshopby/' . $filterName . '_filter/' . $configName,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getCustomDataForCategoryFilter()
    {
        $data = [];
        foreach ($this->getKeyValueForCategoryFilterConfig() as $key => $value) {
            $data[$key] = $this->scopeConfig->getValue($value, ScopeInterface::SCOPE_WEBSITES);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getKeyValueForCategoryFilterConfig()
    {
        return [
            'category_tree_depth' => 'dsshopby/category_filter/category_tree_depth',
            'subcategories_view' => 'dsshopby/category_filter/subcategories_view',
            'subcategories_expand' => 'dsshopby/category_filter/subcategories_expand',
            'render_all_categories_tree' => 'dsshopby/category_filter/render_all_categories_tree',
            'render_categories_level' => 'dsshopby/category_filter/render_categories_level',
        ];
    }

    public function getFilterSettingByCode(?string $code): ?FilterSettingInterface
    {
        return $this->filterResolver->getFilterSettingByCode($code);
    }
}
