<?php

namespace Dotsquares\ShopbyBase\Test\Unit\Model\Customizer;

use Dotsquares\ShopbyBase\Model\Customizer\Category;
use Magento\Catalog\Model\Category as CatalogCategory;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Dotsquares\ShopbyBase\Test\Unit\Traits;

/**
 * Class CategoryTest
 *
 * @see Category
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class CategoryTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @var Category
     */
    private $model;

    protected function setUp(): void
    {
        /** @var \Dotsquares\ShopbySeo\Model\Customizer\Category\Seo|MockObject $seoModifier */
        $seoModifier = $this->createPartialMock(\Dotsquares\ShopbySeo\Model\Customizer\Category\Seo::class, ['prepareData']);
        $seoModifier->expects($this->any())->method('prepareData')->willReturnCallback(
            function ($category) {
                $category->setData('seo', true);
            }
        );
        /** @var \Magento\Framework\ObjectManager\ObjectManager|MockObject $objectManager */
        $objectManager = $this->createPartialMock(\Magento\Framework\ObjectManager\ObjectManager::class, ['get']);
        $objectManager->expects($this->any())->method('get')->willReturnCallback(
            function ($className) use ($seoModifier) {
                if ($className == \Dotsquares\ShopbySeo\Model\Customizer\Category\Seo::class) {
                    return $seoModifier;
                }

                return null;
            }
        );

        $this->model = $this->getObjectManager()->getObject(Category::class, [
            'objectManager' => $objectManager,
            'customizers' => ['seo' => \Dotsquares\ShopbySeo\Model\Customizer\Category\Seo::class]
        ]);
    }

    /**
     * @covers Category::_modifyData
     *
     * @throws \ReflectionException
     */
    public function testModifyData()
    {
        $category = $this->getObjectManager()->getObject(CatalogCategory::class);
        $this->invokeMethod($this->model, '_modifyData', ['seo', $category]);

        $this->assertTrue($category->getData('seo'));

        $category = $this->getObjectManager()->getObject(CatalogCategory::class);
        $this->invokeMethod($this->model, '_modifyData', ['brand', $category]);

        $this->assertEmpty($category->getData());
    }
}
