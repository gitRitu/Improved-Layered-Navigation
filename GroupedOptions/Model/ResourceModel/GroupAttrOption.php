<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Model\ResourceModel;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrOptionInterface;
use Dotsquares\GroupedOptions\Api\GroupRepositoryInterface;

class GroupAttrOption extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init(GroupRepositoryInterface::TABLE_OPTIONS, GroupAttrOptionInterface::ID);
    }
}
