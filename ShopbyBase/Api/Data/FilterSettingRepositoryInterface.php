<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Api\Data;

use Magento\Framework\Api\SearchCriteriaInterface as SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface FilterSettingRepositoryInterface
{
    public const TABLE = 'dotsquares_dsshopby_filter_setting';

    /**
     * @param string $code
     * @param string|null $idFieldName
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     * @throws NoSuchEntityException
     */
    public function get($code, $idFieldName = null);

    /**
     * @param string $attributeCode
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting(string $attributeCode): FilterSettingInterface;

    /**
     * @param string $attributeCode
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface|null
     */
    public function getByAttributeCode(string $attributeCode): ?FilterSettingInterface;

    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    public function save(FilterSettingInterface $filterSetting): FilterSettingInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param string $attributeCode
     * @return void
     */
    public function deleteByAttributeCode(string $attributeCode): void;

    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function update(FilterSettingInterface $filterSetting): FilterSettingInterface;
}
