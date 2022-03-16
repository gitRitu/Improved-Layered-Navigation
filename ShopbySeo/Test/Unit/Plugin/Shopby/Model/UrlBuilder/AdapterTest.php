<?php

namespace Dotsquares\ShopbySeo\Test\Unit\Plugin\Shopby\Model\UrlBuilder;

use Dotsquares\ShopbySeo\Plugin\Shopby\Model\UrlBuilder\Adapter;
use Dotsquares\ShopbySeo\Test\Unit\Traits;

/**
 * Class Adapter
 *
 * @see Adapter
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class AdapterTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    public const SEO_SUFFIX = '_suffix';

    public const TEST_RESULT = 'test';

    /**
     * @covers Adapter::afterGetSuffix
     * @dataProvider afterGetSuffixDataProvider
     */
    public function testAfterGetSuffix($isAddSuffix, $expected)
    {
        $subject = $this->createMock(\Dotsquares\Shopby\Model\UrlBuilder\Adapter::class);

        $urlHelper = $this->createMock(\Dotsquares\ShopbySeo\Helper\Url::class);
        $urlHelper->expects($this->any())->method('isAddSuffixToShopby')
            ->willReturn($isAddSuffix);
        $urlHelper->expects($this->any())->method('getSeoSuffix')
            ->willReturn(self::SEO_SUFFIX);

        $adapter = $this->createPartialMock(
            \Dotsquares\ShopbySeo\Plugin\Shopby\Model\UrlBuilder\Adapter::class,
            []
        );
        $this->setProperty($adapter, 'urlHelper', $urlHelper, Adapter::class);
        $this->assertEquals($expected, $adapter->afterGetSuffix($subject, self::TEST_RESULT));
    }

    /**
     * Data provider for afterGetSuffix test
     * @return array
     */
    public function afterGetSuffixDataProvider()
    {
        return [
            [false, self::TEST_RESULT],
            [true, self::SEO_SUFFIX]
        ];
    }
}
