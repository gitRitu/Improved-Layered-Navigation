<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Provider\Collector;

interface CollectorInterface
{
    public function get(): array;
}
