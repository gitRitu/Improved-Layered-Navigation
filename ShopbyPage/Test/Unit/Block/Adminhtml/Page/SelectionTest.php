<?php

namespace Dotsquares\ShopbyPage\Test\Unit\Block\Adminhtml\Page;

use Dotsquares\ShopbyPage\Block\Adminhtml\Page\Selection;
use Dotsquares\ShopbyPage\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

class SelectionTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;

    public const TEST_COUNTER = ['test1', 'test2', 'test3', 'test4', 'test5'];

    /**
     * @var Selection
     */
    private $block;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Dotsquares\ShopbyPage\Model\Page
     */
    private $model;

    public function setUp(): void
    {
        $this->registry = $this->createMock(\Magento\Framework\Registry::class);
        $this->model = $this->getMockBuilder(\Dotsquares\ShopbyPage\Model\Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConditions'])
            ->getMock();
        $this->registry->expects($this->once())
            ->method('registry')
            ->will($this->returnValue($this->model));

        $this->block = $this->getObjectManager()->getObject(
            Selection::class,
            [
                '_coreRegistry' => $this->registry
            ]
        );
    }

    /**
     * @covers Selection::getCounter
     *
     * @throws \ReflectionException
     */
    public function testGetCounter()
    {
        $this->model->expects($this->any())
            ->method('getConditions')
            ->will($this->returnValue(self::TEST_COUNTER));
        $this->assertEquals(count(self::TEST_COUNTER), $this->block->getCounter());
    }
}
