<?php

namespace Dotsquares\Shopby\Test\Unit\Model\Source;

use Dotsquares\Shopby\Test\Unit\Traits;

class DisplayModeTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @var \Dotsquares\Shopby\Model\Source\DisplayMode
     */
    private $model;

    /**
     * @var \Dotsquares\Shopby\Model\Source\DisplayMode
     */
    private $attribute;

    public function setup(): void
    {
        $this->model = $this->getObjectManager()->getObject(\Dotsquares\Shopby\Model\Source\DisplayMode::class, []);
        $this->attribute = $this->createPartialMock(
            \Magento\Catalog\Model\ResourceModel\Eav\Attribute::class,
            ['getId', 'getFrontendInput']
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testShowSwatchOptionsWithoutAttribute()
    {
        $this->assertFalse($this->invokeMethod($this->model, 'showSwatchOptions'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testShowSwatchOptionsWithoutId()
    {
        $this->attribute->method('getId')->willReturn(0);
        $this->model->setAttribute($this->attribute);

        $this->assertFalse($this->invokeMethod($this->model, 'showSwatchOptions'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testShowSwatchOptions()
    {
        $this->attribute->method('getId')->willReturn(1);
        $this->attribute->method('getFrontendInput')->will($this->onConsecutiveCalls('select', 'multiselect', 'price'));
        $this->model->setAttribute($this->attribute);

        $this->assertTrue($this->invokeMethod($this->model, 'showSwatchOptions'));
        $this->assertTrue($this->invokeMethod($this->model, 'showSwatchOptions'));
        $this->assertFalse($this->invokeMethod($this->model, 'showSwatchOptions'));
    }
}
