<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Model\Backend\Group;

use Dotsquares\GroupedOptions\Api\Data\GroupAttrInterface;

class Registry
{
    /**
     * @var GroupAttrInterface|null
     */
    private $group;

    public function getGroup(): ?GroupAttrInterface
    {
        return $this->group;
    }

    public function setGroup(GroupAttrInterface $group): void
    {
        $this->group = $group;
    }
}
