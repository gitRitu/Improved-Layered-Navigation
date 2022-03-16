<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Block\Navigation;

use Dotsquares\Shopby\Helper\Data as ShopbyHelper;
use Dotsquares\Shopby\Helper\FilterSetting;
use Dotsquares\Shopby\Helper\UrlBuilder as ShopbyUrlBuilder;
use Dotsquares\Shopby\Model\ConfigProvider;
use Dotsquares\Shopby\Model\UrlResolver\UrlResolverInterface;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Model\FilterSetting\IsMultiselect;
use Dotsquares\ShopbyBase\Model\FilterSetting\IsShowProductQuantities;
use Dotsquares\ShopbyBase\Model\Integration\Shopby\GetConfigProvider;
use Dotsquares\ShopbyBase\Model\OptionSettingRepository;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory;
use Magento\Eav\Model\Entity\Attribute;
use Dotsquares\Shopby\Helper\FilterSetting as FilterSettingHelper;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Framework\View\Element\Template\Context;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered;
use \Magento\Store\Model\Store;
use Magento\Swatches\Helper\Data as SwatchesData;
use Magento\Swatches\Helper\Media;

class SwatchRenderer extends RenderLayered implements RendererInterface
{
    public const SWATCH_TYPE_OPTION_IMAGE = 'option_image';
    public const VAR_COUNT = 'dotsquares_shopby_count';
    public const VAR_FILTER_ITEM = 'dotsquares_shopby_filter_item';

    public const FILTERABLE_NO_RESULTS = '2';

    /**
     * @var ShopbyUrlBuilder
     */
    private $urlBuilderHelper;

    /**
     * @var FilterSettingHelper
     */
    private $filterSettingHelper;

    /**
     * @var \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    private $filterSetting;

    /**
     * @var ShopbyHelper
     */
    private $helper;

    /**
     * @var OptionSettingRepository
     */
    private $optionSettingRepository;

    /**
     * @var UrlResolverInterface
     */
    private $urlResolver;

    /**
     * @var string
     */
    protected $_template = 'Dotsquares_Shopby::layer/filter/swatch/default.phtml';

    /**
     * @var IsShowProductQuantities
     */
    private $isShowProductQuantities;

    /**
     * @var ConfigProvider|null
     */
    private $configProvider;

    /**
     * @var IsMultiselect
     */
    private $isMultiselect;

    public function __construct(
        Context $context,
        Attribute $eavAttribute,
        AttributeFactory $layerAttribute,
        SwatchesData $swatchHelper,
        Media $mediaHelper,
        ShopbyUrlBuilder $urlBuilderHelper,
        ShopbyHelper $helper,
        OptionSettingRepository $optionSettingRepository,
        FilterSettingHelper $filterSettingHelper,
        UrlResolverInterface $urlResolver,
        IsShowProductQuantities $isShowProductQuantities,
        GetConfigProvider $getConfigProvider,
        IsMultiselect $isMultiselect,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $eavAttribute,
            $layerAttribute,
            $swatchHelper,
            $mediaHelper,
            $data
        );
        $this->filterSettingHelper = $filterSettingHelper;
        $this->helper = $helper;
        $this->optionSettingRepository = $optionSettingRepository;
        $this->urlBuilderHelper = $urlBuilderHelper;
        $this->urlResolver = $urlResolver;
        $this->isShowProductQuantities = $isShowProductQuantities;
        $this->configProvider = $getConfigProvider->execute();
        $this->isMultiselect = $isMultiselect;
    }

    /**
     * @param string $attributeCode
     * @param int $optionId
     * @return string
     */
    public function buildUrl($attributeCode, $optionId)
    {
        return $this->urlBuilderHelper->buildUrl($this->filter, $optionId);
    }

    /**
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting()
    {
        if ($this->filterSetting === null) {
            $this->filterSetting = $this->filterSettingHelper->getSettingByLayerFilter($this->filter);
        }
        return $this->filterSetting;
    }

    /**
     * @return array
     */
    public function getSwatchData()
    {
        $swatchData = parent::getSwatchData();
        unset($swatchData['options']['']);
        foreach ($this->getMultiSelectSwatches(array_keys($swatchData['options'])) as $id => $value) {
            $swatchData['swatches'][$id] = $value;
        }

        if ($this->getFilterSetting()->getSortOptionsBy() == \Dotsquares\Shopby\Model\Source\SortOptionsBy::NAME) {
            uasort($swatchData['options'], [$this, 'sortSwatchData']);
        }

        $swatchData['options'] = $this->sortingByFeatures($swatchData);

        return $swatchData;
    }

    /**
     * @param $swatchData
     * @return array
     */
    private function sortingByFeatures($swatchData)
    {
        $attribute = $this->getFilterSetting()->getAttributeModel();
        $featuredOptionArray = [];
        $optionKeys = array_keys($swatchData['options']);
        $featuredOptions = $this->optionSettingRepository->getAllFeaturedOptionsArray($this->getStoreId());
        $filterCode = FilterSetting::ATTR_PREFIX . $attribute->getAttributeCode();
        foreach ($swatchData['swatches'] as $key => $option) {
            if ($this->isOptionFeatured($featuredOptions, $filterCode, $option)) {
                $keyPosition = array_search($key, $optionKeys);
                if ($keyPosition) {
                    unset($optionKeys[$keyPosition]);
                }
                $featuredOptionArray[] = $key;
            }
            $swatchData['options'][$key]['key'] = $key;
        }
        $optionKeys = array_merge($featuredOptionArray, $optionKeys);

        $options = [];
        foreach ($optionKeys as $key) {
            $options[$key] = $swatchData['options'][$key];
        }

         return $options;
    }

    /**
     * @param array $options
     * @param string $filterCode
     * @param array $option
     * @return bool
     */
    private function isOptionFeatured($options, $filterCode, $option)
    {
        $isFeatured = false;
        if (isset($option['option_id'])) {
            $isFeatured = isset($options[$filterCode][$option['option_id']][$this->getStoreId()])
                || isset($options[$filterCode][$option['option_id']][Store::DEFAULT_STORE_ID]);
        }

        return $isFeatured;
    }

    /**
     * Retrieve current store id scope
     *
     * @return int
     */
    public function getStoreId()
    {
        $storeId = $this->_getData('store_id');
        if ($storeId === null) {
            $storeId = $this->_storeManager->getStore()->getId();
        }
        return $storeId;
    }

    /**
     * @param array $optionIds
     * @return array
     */
    private function getMultiSelectSwatches($optionIds)
    {
        $attribute = $this->getFilterSetting()->getAttributeModel();
        return $this->helper->getSwatchesFromImages($optionIds, $attribute);
    }

    /**
     * Fix Magento logic
     *
     * @param FilterItem $filterItem
     * @return bool
     */
    protected function isOptionVisible(FilterItem $filterItem)
    {
        return !$this->isOptionDisabled($filterItem) || $this->isShowEmptyResults();
    }

    /**
     * Fix Magento logic
     *
     * @return bool
     */
    protected function isShowEmptyResults()
    {
        return $this->eavAttribute->getIsFilterable() === self::FILTERABLE_NO_RESULTS;
    }

    /**
     * @param FilterItem $filterItem
     * @param Option $swatchOption
     * @return array
     */
    protected function getOptionViewData(FilterItem $filterItem, Option $swatchOption)
    {
        $data = parent::getOptionViewData($filterItem, $swatchOption);
        $data[self::VAR_COUNT] = $filterItem->getCount();
        $data[self::VAR_FILTER_ITEM] = $filterItem;

        return $data;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public function sortSwatchData($a, $b)
    {
        $pattern = '@^(\d+)@';
        if (preg_match($pattern, $a['label'], $ma) && preg_match($pattern, $b['label'], $mb)) {
            $r = $ma[1] - $mb[1];
            if ($r != 0) {
                return $r;
            }
        }

        return strcasecmp($a['label'], $b['label']);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTooltipHtml()
    {
        return $this->getLayout()->createBlock(
            \Dotsquares\Shopby\Block\Navigation\Widget\Tooltip::class
        )
            ->setFilterSetting($this->getFilterSetting())
            ->toHtml();
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = parent::toHtml();

        if ($this->isShowTooltip($this->getFilterSetting()->getTooltip())) {
            $html .= $this->getTooltipHtml();
        }

        $html .= $this->filterSettingHelper->getShowMoreButtonBlock($this->getFilterSetting())->toHtml();
        return $html;
    }

    private function isShowTooltip(?string $tooltip): bool
    {
        return $this->configProvider && $this->configProvider->isTooltipsEnabled() && !empty($tooltip);
    }

    /**
     * @param \Dotsquares\Shopby\Model\Layer\Filter\Item $filterItem
     * @return int
     */
    public function isFilterItemSelected(\Dotsquares\Shopby\Model\Layer\Filter\Item $filterItem)
    {
        return $this->helper->isFilterItemSelected($filterItem);
    }

    /**
     * @return bool
     */
    public function collectFilters()
    {
        return $this->helper->collectFilters();
    }

    /**
     * @return string
     */
    public function getClearUrl(): string
    {
        return $this->urlResolver->resolve();
    }

    /**
     * @return \Magento\Catalog\Model\Layer\Filter\AbstractFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param $swatchOption
     * @return array
     */
    protected function getUnusedOptionGroup($swatchOption)
    {
        $customStyle = '';
        $linkToOption = $this->buildUrl($this->eavAttribute->getAttributeCode(), $swatchOption->getGroupCode());
        return [
            'label' => $swatchOption->getName(),
            'link' => $linkToOption,
            'custom_style' => $customStyle,
            self::VAR_COUNT => 0
        ];
    }

    /**
     * @return string
     */
    public function getSearchForm()
    {
        return $this->getLayout()->createBlock(
            \Dotsquares\Shopby\Block\Navigation\Widget\SearchForm::class
        )
            ->assign('filterCode', $this->getFilterSetting()->getAttributeCode())
            ->setFilter($this->filter)
            ->toHtml();
    }

    public function isShowProductQuantities(?int $showProductQuantities): bool
    {
        return $this->isShowProductQuantities->execute($showProductQuantities);
    }

    public function isMultiselect(FilterSettingInterface $filterSetting): bool
    {
        return $this->isMultiselect->execute(
            $filterSetting->getAttributeCode(),
            $filterSetting->isMultiselect(),
            $filterSetting->getDisplayMode()
        );
    }
}
