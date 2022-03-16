<?php

namespace Dotsquares\ShopbyBase\Test\Unit\Model\FilterSetting;

use Dotsquares\ShopbyBase\Model\FilterSetting\AttributeConfig;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Dotsquares\ShopbyBase\Test\Unit\Traits;

/**
 * Class AttributeConfigTest
 *
 * @see AttributeConfig
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class AttributeConfigTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ReflectionTrait;
    use Traits\ObjectManagerTrait;

    /**
     * @var AttributeConfig
     */
    private $model;

    protected function setUp(): void
    {
        /** @var \Dotsquares\ShopbyBrand\Model\FilterSetting\AttributeListProvider|MockObject $provider */
        $provider = $this->createPartialMock(
            \Dotsquares\ShopbyBrand\Model\FilterSetting\AttributeListProvider::class,
            ['getAttributeList']
        );
        $provider->expects($this->once())->method('getAttributeList')->willReturn(
            ['attr_1' => true, 'attr_2' => false]
        );

        $this->model =
            $this->getObjectManager()->getObject(AttributeConfig::class, ['attributeProviders' => [$provider]]);
    }

    /**
     * @covers AttributeConfig::canBeConfigured
     *
     * @throws \ReflectionException
     */
    public function testCanBeConfigured()
    {
        $this->assertFalse($this->model->canBeConfigured('attr_2'));
        $this->assertTrue($this->model->canBeConfigured('attr_1'));

        $this->setProperty($this->model, 'attributeList', [AttributeConfig::ALL_ATTRIBUTES_PARAM => true]);

        $this->assertTrue($this->model->canBeConfigured('attr_2'));
    }
}
