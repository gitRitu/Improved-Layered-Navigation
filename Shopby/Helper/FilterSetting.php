<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Helper;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Model\FilterSetting\FilterResolver;
use Dotsquares\ShopbyBase\Model\FilterSettingFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Store\Model\ScopeInterface;

class FilterSetting extends \Dotsquares\ShopbyBase\Helper\FilterSetting
{
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    public function __construct(
        Context $context,
        FilterSettingFactory $settingFactory,
        BlockFactory $blockFactory,
        FilterResolver $filterResolver
    ) {
        parent::__construct($context, $settingFactory, $filterResolver);
        $this->blockFactory = $blockFactory;
    }

    public function getSettingByLayerFilter(FilterInterface $layerFilter): ?FilterSettingInterface
    {
        $attributeCode = $this->getFilterCode($layerFilter);
        $setting = $this->getFilterSettingByCode($attributeCode);

        $setting->setAttributeModel($layerFilter->getData('attribute_model'));

        return $setting;
    }

    /**
     * @param FilterInterface $layerFilter
     * @return string|null
     */
    public function getFilterCode(FilterInterface $layerFilter)
    {
        $attribute = $layerFilter->getData('attribute_model');
        $filterCode = is_object($attribute) ? $attribute->getAttributeCode() : null;

        if (!$filterCode) {
            if ($layerFilter instanceof \Dotsquares\Shopby\Model\Layer\Filter\Category) {
                $filterCode = \Dotsquares\Shopby\Helper\Category::ATTRIBUTE_CODE;
            } elseif ($layerFilter instanceof \Dotsquares\Shopby\Model\Layer\Filter\Stock) {
                $filterCode = 'stock';
            } elseif ($layerFilter instanceof \Dotsquares\Shopby\Model\Layer\Filter\Rating) {
                $filterCode = 'rating';
            } elseif ($layerFilter instanceof \Dotsquares\Shopby\Model\Layer\Filter\IsNew) {
                $filterCode = 'ds_is_new';
            } elseif ($layerFilter instanceof \Dotsquares\Shopby\Model\Layer\Filter\OnSale) {
                $filterCode = 'ds_on_sale';
            }
        }

        return $filterCode;
    }

    /**
     * @return string
     */
    public function getShowMoreButtonBlock($setting)
    {
        return $this->blockFactory->createBlock(\Dotsquares\Shopby\Block\Navigation\Widget\HideMoreOptions::class)
            ->setFilterSetting($setting);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isSetConfig($path)
    {
        return $this->scopeConfig->isSetFlag(
            $path,
            ScopeInterface::SCOPE_STORE
        );
    }
}
