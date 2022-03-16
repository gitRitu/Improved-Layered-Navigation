<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Model\ResourceModel\Search\FilterMapper\CustomExclusionStrategy;

use Magento\Framework\DB\Select;
use Magento\Framework\Search\Request\FilterInterface;

interface OperationInterface
{
    public function applyFilter(FilterInterface $filter, Select $select): void;
}
