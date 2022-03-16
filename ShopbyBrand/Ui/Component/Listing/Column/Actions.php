<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBrand\Ui\Component\Listing\Column;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBrand\Ui\Component\Listing\Columns\BrandAttribute;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = (int) $this->context->getFilterParam('scope') ?: 0;
            foreach ($dataSource['data']['items'] as &$item) {
                $code = $item[BrandAttribute::ORIG_ATTRIBUTE_CODE]
                    ?? $item[FilterSettingInterface::ATTRIBUTE_CODE]
                    ?? null;
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'dotsquares_shopbybrand/slider/edit',
                        ['attribute_code' => $code, 'option_id' => $item['option_id'], 'store' => $storeId]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
