<?php

declare(strict_types=1);

namespace Dotsquares\Base\Test\Unit\Model\SysInfo\Provider;

use Dotsquares\Base\Model\SysInfo\Provider\Collector;
use Dotsquares\Base\Model\SysInfo\Provider\Collector\CollectorInterface;
use Dotsquares\Base\Model\SysInfo\Provider\CollectorPool;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
    /**
     * @var Collector
     */
    private $model;

    /**
     * @var CollectorPool|MockObject
     */
    private $collectorPoolMock;

    protected function setUp(): void
    {
        $this->collectorPoolMock = $this->createMock(CollectorPool::class);

        $this->model = new Collector(
            $this->collectorPoolMock
        );
    }

    public function testCollect(): void
    {
        $groupName = 'name';
        $collectorName = 'collectorName';
        $collectorData = ['val', 'val'];
        $collectorMock = $this->createMock(CollectorInterface::class);
        $collectors = ['collectorName' => $collectorMock];
        $expected = [$collectorName => $collectorData];

        $this->collectorPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($groupName)
            ->willReturn($collectors);
        $collectorMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($collectorData);

        $this->assertEquals($expected, $this->model->collect($groupName));
    }
}
