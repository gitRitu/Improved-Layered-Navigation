<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Model\Repository;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrInterface;
use Dotsquares\GroupedOptions\Api\Data\GroupAttrRepositoryInterface;
use Dotsquares\GroupedOptions\Model\GroupAttrFactory;
use Dotsquares\GroupedOptions\Model\ResourceModel\GroupAttr as GroupAttrResource;
use Magento\Framework\Exception\NoSuchEntityException;

class GroupAttrRepository implements GroupAttrRepositoryInterface
{
    /**
     * @var GroupAttrResource
     */
    private $resource;

    /**
     * @var GroupAttrInterface
     */
    private $factory;

    public function __construct(
        GroupAttrResource $resource,
        GroupAttrFactory $factory
    ) {
        $this->resource = $resource;
        $this->factory = $factory;
    }

    /**
     * @param int $id
     * @return GroupAttrInterface
     * @throws NoSuchEntityException
     */
    public function get($id): GroupAttrInterface
    {
        $entity = $this->factory->create();
        $this->resource->load($entity, $id);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Requested attribute group doesn\'t exist'));
        }
        return $entity;
    }

    /**
     * @param GroupAttrInterface $entity
     * @return $this
     */
    public function save(GroupAttrInterface $entity)
    {
        $this->resource->save($entity);
        return $this;
    }

    /**
     * @param GroupAttrInterface $entity
     * @return $this
     */
    public function delete(GroupAttrInterface $entity)
    {
        $this->resource->delete($entity);
        return $this;
    }
}
