<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Provider;

use Magento\Framework\Exception\NotFoundException;

class Collector
{
    /**
     * @var CollectorPool
     */
    private $collectorPool;

    public function __construct(CollectorPool $collectorPool)
    {
        $this->collectorPool = $collectorPool;
    }

    /**
     * @param string $groupName
     * @return array
     * @throws NotFoundException
     */
    public function collect(string $groupName): array
    {
        $data = [];
        $collectors = $this->collectorPool->get($groupName);
        foreach ($collectors as $collectorName => $collector) {
            $data[$collectorName] = $collector->get();
        }

        return $data;
    }
}
