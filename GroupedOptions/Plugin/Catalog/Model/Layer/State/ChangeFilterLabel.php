<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Plugin\Catalog\Model\Layer\State;

use Dotsquares\GroupedOptions\Plugin\CatalogSearch\Model\Layer\Filter\ChangeDecimalLabels;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\State;
use Magento\CatalogSearch\Model\Layer\Filter\Decimal as DecimalFilter;
use Magento\CatalogSearch\Model\Layer\Filter\Price as PriceFilter;

class ChangeFilterLabel
{
    /**
     * @var ChangeDecimalLabels
     */
    private $changeDecimalLabels;

    public function __construct(ChangeDecimalLabels $changeDecimalLabels)
    {
        $this->changeDecimalLabels = $changeDecimalLabels;
    }

    public function beforeAddFilter(State $subject, Item $itemFilter): void
    {
        $filter = $itemFilter->getFilter();

        if ($filter instanceof PriceFilter || $filter instanceof DecimalFilter) {
            $this->changeDecimalLabels->execute(
                (int) $filter->getAttributeModel()->getAttributeId(),
                [$itemFilter]
            );
        }
    }
}
