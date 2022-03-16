<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Model\GroupAttr\Query;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrInterface;
use Dotsquares\GroupedOptions\Model\ResourceModel\GroupAttr\Collection;
use Dotsquares\GroupedOptions\Model\ResourceModel\GroupAttr\CollectionFactory;

class IsGroupCodeExist
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(string $code): bool
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        return (bool) $collection->addFieldToFilter(GroupAttrInterface::GROUP_CODE, $code)->getSize();
    }
}
