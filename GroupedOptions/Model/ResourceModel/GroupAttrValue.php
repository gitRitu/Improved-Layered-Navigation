<?php

namespace Dotsquares\GroupedOptions\Model\ResourceModel;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrValueInterface;
use Dotsquares\GroupedOptions\Api\GroupRepositoryInterface;

class GroupAttrValue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init(GroupRepositoryInterface::TABLE_VALUES, GroupAttrValueInterface::ID);
    }
}
