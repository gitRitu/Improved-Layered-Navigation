<?php

namespace Dotsquares\Shopby\Plugin\Catalog\Block\Product\View;

use Dotsquares\Mage24Fix\Block\Theme\Html\Title;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Block\Product\AttributeIcon;
use Dotsquares\ShopbyBase\Helper\Data;
use Dotsquares\ShopbyBase\Helper\FilterSetting as FilterHelper;
use Dotsquares\ShopbyBase\Model\OptionSetting;
use Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting\Collection;
use Dotsquares\ShopbyBase\Model\ResourceModel\FilterSetting\CollectionFactory as FilterCollectionFactory;
use Dotsquares\ShopbyBase\ViewModel\OptionProcessor;
use Magento\Framework\View\Element\BlockFactory;

class BlockHtmlTitlePlugin
{
    /**
     * @var Data
     */
    private $baseHelper;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var OptionProcessor
     */
    private $optionProcessor;

    /**
     * @var FilterCollectionFactory
     */
    private $filterCollectionFactory;

    public function __construct(
        Data $baseHelper,
        BlockFactory $blockFactory,
        OptionProcessor $optionProcessor,
        FilterCollectionFactory $filterCollectionFactory
    ) {
        $this->baseHelper = $baseHelper;
        $this->blockFactory = $blockFactory;
        $this->optionProcessor = $optionProcessor;
        $this->filterCollectionFactory = $filterCollectionFactory;
    }

    /**
     * Add Brand Label to Product Page
     *
     * @param \Magento\Theme\Block\Html\Title|Title $original
     * @param $html
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterToHtml($original, $html)
    {
        $logoHtml = $this->generateLogoHtml();

        return str_replace('/h1>', '/h1>' . $logoHtml, $html);
    }

    /**
     * @return string
     */
    private function generateLogoHtml(): string
    {
        /** @var AttributeIcon $block */
        $attributeCodes = $this->getAttributeCodes();
        if (!$attributeCodes) {
            return '';
        }

        $block = $this->blockFactory->createBlock(
            AttributeIcon::class,
            [
                'data' => [
                    AttributeIcon::KEY_ATTRIBUTE_CODES => $attributeCodes,
                    AttributeIcon::KEY_OPTION_PROCESSOR => $this->optionProcessor,
                ]
            ]
        );

        return $block->toHtml();
    }

    /**
     * @return array
     */
    private function getAttributeCodes(): array
    {
        $attributeCodes = [];

        /** @var Collection $collection */
        $collection = $this->filterCollectionFactory->create();
        $collection->addFieldToSelect(OptionSetting::FILTER_CODE)
            ->addFieldToFilter(FilterSettingInterface::SHOW_ICONS_ON_PRODUCT, true);

        foreach ($collection->getData() as $filterData) {
            $attributeCodes[] = substr(
                $filterData[OptionSetting::FILTER_CODE],
                strlen(FilterHelper::ATTR_PREFIX)
            );
        }

        $brandCode = $this->baseHelper->getBrandAttributeCode();
        $brandCode = $brandCode ? [$brandCode] : [];

        return array_diff($attributeCodes, $brandCode);
    }
}
