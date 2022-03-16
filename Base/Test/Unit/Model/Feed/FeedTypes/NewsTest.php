<?php

namespace Dotsquares\Base\Test\Unit\Model\Feed\FeedTypes;

use Dotsquares\Base\Helper\Module;
use Dotsquares\Base\Model\Feed\FeedTypes\News;
use Dotsquares\Base\Model\ModuleInfoProvider;
use Dotsquares\Base\Test\Unit\Traits;
use Magento\Framework\DataObjectFactory;

class NewsTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @var News
     */
    private $model;

    /**
     * @var Module
     */
    private $moduleInfoProvider;

    protected function setUp(): void
    {
        $moduleList = $this->createMock(\Magento\Framework\Module\ModuleListInterface::class);
        $this->moduleInfoProvider = $this->createMock(ModuleInfoProvider::class);

        $moduleList->expects($this->any())->method('getNames')->willReturn(['Magento_Catalog', 'Dotsquares_Seo']);

        $dataObjectFactory = $this->createPartialMock(DataObjectFactory::class, ['create']);
        $dataObjectFactory->expects($this->any())->method('create')->willReturn(
            new \Magento\Framework\DataObject()
        );

        $this->model = $this->getObjectManager()->getObject(
            News::class,
            [
                'moduleList' => $moduleList,
                'moduleInfoProvider' => $this->moduleInfoProvider,
                'dataObjectFactory' => $dataObjectFactory
            ]
        );
    }

    /**
     * @covers NewsProcessor::getInstalledDotsquaresExtensions
     */
    public function testGetInstalledDotsquaresExtensions()
    {
        $this->assertEquals([1 => 'Dotsquares_Seo'], $this->invokeMethod($this->model, 'getInstalledDotsquaresExtensions'));
    }

    /**
     * @covers NewsProcessor::validateByExtension
     * @dataProvider validateByExtensionDataProvider
     */
    public function testValidateByExtension($extensions, $result)
    {
        $this->assertEquals($result, $this->invokeMethod($this->model, 'validateByExtension', [$extensions, true]));
    }

    /**
     * Data provider for validateByExtension test
     * @return array
     */
    public function validateByExtensionDataProvider()
    {
        return [
            ['', true],
            ['Magento_Catalog,Dotsquares_Seo', true],
            ['test', false],
        ];
    }

    /**
     * @covers NewsProcessor::validateByNotInstalled
     * @dataProvider validateByNotInstalledDataProvider
     */
    public function testValidateByNotInstalled($extensions, $result)
    {
        $this->assertEquals($result, $this->invokeMethod($this->model, 'validateByNotInstalled', [$extensions, true]));
    }

    /**
     * Data provider for validateByNotInstalled test
     * @return array
     */
    public function validateByNotInstalledDataProvider()
    {
        return [
            ['', true],
            ['Magento_Catalog,Dotsquares_Seo', true],
            ['Dotsquares_Seo', false],
        ];
    }

    /**
     * @covers NewsProcessor::getDependModules
     */
    public function testGetDependModules()
    {
        $this->moduleInfoProvider->expects($this->any())->method('getModuleInfo')
            ->willReturn(['name' => 'dotsquares', 'require' => ['magento' => 'catalog', 'dotsquares' => 'shopby']]);
        $this->assertEquals(['Dotsquares_Seo'], $this->invokeMethod($this->model, 'getDependModules', [['Dotsquares_Seo']]));
    }
}
