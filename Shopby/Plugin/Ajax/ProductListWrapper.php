<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Plugin\Ajax;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\App\Request\Http;

class ProductListWrapper
{
    /**
     * @var Http
     */
    private $request;

    public function __construct(
        Http $request
    ) {
        $this->request = $request;
    }

    public function afterToHtml(ListProduct $subject, string $result): string
    {
        if ($subject->getNameInLayout() !== 'category.products.list'
            && $subject->getNameInLayout() !== 'search_result_list'
        ) {
            return $result;
        }

        if ($this->request->getParam('is_scroll')) {
            return $result;
        }

        return sprintf('<div id="dotsquares-shopby-product-list">%s</div>', $result);
    }
}
