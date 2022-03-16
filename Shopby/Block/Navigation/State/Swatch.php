<?php

namespace Dotsquares\Shopby\Block\Navigation\State;

use Dotsquares\Shopby\Model\Layer\Filter\Item;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\View\Element\Template;

class Swatch extends Template
{
    /**
     * @var  Item
     */
    protected $filter;

    /**
     * @var \Magento\Swatches\Helper\Data
     */
    protected $swatchHelper;

    /**
     * @var \Magento\Swatches\Helper\Media
     */
    protected $mediaHelper;

    /**
     * @var \Dotsquares\Shopby\Helper\Data
     */
    private $dsshopbyHelper;

    /**
     * @var bool
     */
    private $showLabels;

    /**
     * @var \Dotsquares\Shopby\Helper\FilterSetting
     */
    private $filterSettingHelper;

    public function __construct(
        Template\Context $context,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Swatches\Helper\Media $mediaHelper,
        \Dotsquares\Shopby\Helper\Data $dsshopbyHelper,
        \Dotsquares\Shopby\Helper\FilterSetting $filterSettingHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->swatchHelper = $swatchHelper;
        $this->mediaHelper = $mediaHelper;
        $this->dsshopbyHelper = $dsshopbyHelper;
        $this->filterSettingHelper = $filterSettingHelper;
    }

    /**
     * @param Item $filter
     * @return $this
     */
    public function setFilter(Item $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @param $showLabels
     * @return $this
     */
    public function showLabels($showLabels)
    {
        $this->showLabels = $showLabels;
        return $this;
    }

    public function getTemplate(): string
    {
        return 'Dotsquares_Shopby::layer/filter/swatch/default.phtml';
    }

    public function getSwatchData(): array
    {
        $attributeOptions = [];
        $filterAppliedValues = $this->getFilterAppliedValues();
        $eavAttribute = $this->getEavAttribute();
        $swatches = $this->getSwatches($filterAppliedValues, $eavAttribute);

        foreach ($filterAppliedValues as $value) {
            $attributeOptions[$value] = [
                'link' => '#',
                'custom_style' => '',
                'label' => $this->filter->getOptionLabel()
            ];
        }

        return [
            'attribute_id' => $eavAttribute->getId(),
            'attribute_code' => $eavAttribute->getAttributeCode(),
            'attribute_label' => $eavAttribute->getStoreLabel(),
            'options' => [$attributeOptions[$this->filter->getValue()] ?? []],
            'swatches' => [$swatches[$this->filter->getValue()] ?? []]
        ];
    }

    /**
     * plugin for Dotsquares\GroupedOptions\Plugin\Shopby\Block\Navigation\State\AddGroupedSwatches
     * @param array $filterAppliedValues
     * @param Attribute $eavAttribute
     *
     * @return array
     */
    public function getSwatches(array $filterAppliedValues, Attribute $eavAttribute): array
    {
        $swatches = $this->dsshopbyHelper->getSwatchesFromImages($filterAppliedValues, $eavAttribute);
        $swatches = $swatches + $this->swatchHelper->getSwatchesByOptionsId($filterAppliedValues);

        return $swatches;
    }

    protected function getFilterAppliedValues(): array
    {
        $filterAppliedValues = $this->filter->getValue();
        if (!is_array($filterAppliedValues)) {
            $filterAppliedValues = [$filterAppliedValues];
        }

        return $filterAppliedValues;
    }

    /**
     * @return Attribute
     */
    protected function getEavAttribute(): Attribute
    {
        return $this->filter->getFilter()->getAttributeModel();
    }

    /**
     * @param $attributeCode
     * @return int|null
     */
    public function getDisplayModeByAttributeCode($attributeCode)
    {
        return $this->filterSettingHelper->getFilterSettingByCode($attributeCode)->getDisplayMode();
    }

    /**
     * @return null
     */
    public function getFilterSetting()
    {
        return null;
    }

    /**
     * @param string $type
     * @param string $filename
     * @return string
     */
    public function getSwatchPath($type, $filename)
    {
        $imagePath = $this->mediaHelper->getSwatchAttributeImage($type, $filename);

        return $imagePath;
    }
}
