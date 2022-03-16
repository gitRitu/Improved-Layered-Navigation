<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Model\ResourceModel\GroupAttr;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrOptionInterface;
use Dotsquares\GroupedOptions\Api\Data\GroupAttrValueInterface;
use Dotsquares\GroupedOptions\Api\GroupRepositoryInterface;
use Dotsquares\GroupedOptions\Model\GroupAttr;
use Dotsquares\GroupedOptions\Model\ResourceModel\GroupAttr as GroupAttrResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'group_id';

    protected function _construct()
    {
        $this->_init(GroupAttr::class, GroupAttrResource::class);
    }

    /**
     * @param $name
     * @param $table
     * @param $field
     * @param $where
     * @return $this
     */
    public function joinField($name, $table, $field, $where)
    {
        $this->getSelect()->joinLeft(
            [$name => $this->getTable($table)],
            $name . "." . $where,
            $field
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function joinOptions()
    {
        $this->joinField(
            'aagao',
            GroupRepositoryInterface::TABLE_OPTIONS,
            [GroupAttrOptionInterface::OPTION_ID],
            'group_id=main_table.group_id'
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function joinValues()
    {
        $this->joinField(
            'aagav',
            GroupRepositoryInterface::TABLE_VALUES,
            [GroupAttrValueInterface::VALUE],
            'group_id=main_table.group_id'
        );

        return $this;
    }
}
