<?php

namespace Dotsquares\ShopbyBrand\Plugin\Catalog\Block\Product\View;

use Dotsquares\Mage24Fix\Block\Theme\Html\Title;
use Dotsquares\ShopbyBase\Block\Product\AttributeIcon;
use Dotsquares\ShopbyBrand\Model\ConfigProvider;
use Dotsquares\ShopbyBrand\Model\Source\Tooltip;
use Dotsquares\ShopbyBrand\ViewModel\OptionProcessor;
use Magento\Framework\View\Element\BlockFactory;

class BlockHtmlTitlePlugin
{
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var OptionProcessor
     */
    private $optionProcessor;

    public function __construct(
        BlockFactory $blockFactory,
        ConfigProvider $configProvider,
        OptionProcessor $optionProcessor
    ) {
        $this->blockFactory = $blockFactory;
        $this->configProvider = $configProvider;
        $this->optionProcessor = $optionProcessor;
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
        if ($this->isShowLogo()) {
            $logoHtml = $this->generateLogoHtml();

            $html = str_replace('/h1>', '/h1>' . $logoHtml, $html);
        }

        return $html;
    }

    /**
     * @return string
     */
    private function generateLogoHtml(): string
    {
        $this->optionProcessor->setPageType(Tooltip::PRODUCT_PAGE);

        /** @var AttributeIcon $block */
        $block = $this->blockFactory->createBlock(
            AttributeIcon::class,
            [
                'data' => [
                    AttributeIcon::KEY_ATTRIBUTE_CODES => $this->getAttributeCodes(),
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
        if ($code = $this->configProvider->getBrandAttributeCode()) {
            return [$code];
        }

        return [];
    }

    /**
     * @return bool
     */
    private function isShowLogo(): bool
    {
        return $this->configProvider->isDisplayBrandImage();
    }
}
