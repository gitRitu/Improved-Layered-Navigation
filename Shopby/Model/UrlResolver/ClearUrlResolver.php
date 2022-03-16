<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Model\UrlResolver;

class ClearUrlResolver implements UrlResolverInterface
{
    /**
     * @var \Dotsquares\Shopby\Helper\State
     */
    private $layer;

    /**
     * @var \Dotsquares\ShopbyBase\Api\UrlBuilderInterface
     */
    private $dsUrlBuilder;

    public function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Dotsquares\ShopbyBase\Api\UrlBuilderInterface $dsUrlBuilder
    ) {
        $this->layer = $layerResolver->get();
        $this->dsUrlBuilder = $dsUrlBuilder;
    }

    /**
     * @return array
     */
    private function getActiveFilters(): array
    {
        $filters = $this->layer->getState()->getFilters();
        if (!is_array($filters)) {
            $filters = [];
        }
        return $filters;
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function resolve(): string
    {
        $filterState = ['_' => null, 'shopbyAjax' => null, 'p' => null];
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }

        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->dsUrlBuilder->getUrl('*/*/*', $params);
    }
}
