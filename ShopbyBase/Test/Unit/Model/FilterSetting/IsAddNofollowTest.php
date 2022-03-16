<?php

namespace Dotsquares\ShopbyBase\Test\Unit\Model\FilterSetting;

use Dotsquares\ShopbyBase\Model\FilterSetting;
use Dotsquares\ShopbyBase\Model\FilterSetting\IsAddNofollow;
use Dotsquares\ShopbyBase\Model\Integration\Shopby\GetSelectedFiltersSettings;
use Dotsquares\ShopbyBase\Model\Integration\Shopby\IsBrandPage;
use Dotsquares\ShopbyBase\Model\Integration\ShopbySeo\GetConfigProvider;
use Dotsquares\ShopbyBase\Test\Unit\Traits;
use Dotsquares\ShopbySeo\Model\ConfigProvider;
use Dotsquares\ShopbySeo\Model\Source\IndexMode;
use Dotsquares\ShopbySeo\Model\Source\RelNofollow;
use Magento\Framework\Registry;

/**
 * Class IsAddNofollowTest
 *
 * @see IsAddNofollow
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class IsAddNofollowTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ReflectionTrait;
    use Traits\ObjectManagerTrait;

    /**
     * @covers FilterSetting::isAddNofollow
     *
     * @dataProvider executeDataProvider
     *
     * @throws \ReflectionException
     */
    public function testExecute(
        int $relNofollow,
        int $followMode,
        bool $enableRelNofollow,
        string $isPageNofollow,
        bool $expectedResult
    ) {
        $getConfigProvider = $this->createMock(GetConfigProvider::class);
        $configProvider = $this->createMock(ConfigProvider::class);
        $configProvider->expects($this->any())->method('isEnableRelNofollow')->willReturn($enableRelNofollow);
        $getConfigProvider->expects($this->any())->method('execute')->willReturn($configProvider);

        $getSelectedFiltersSettings = $this->createMock(GetSelectedFiltersSettings::class);
        $isBrandPage = $this->createMock(IsBrandPage::class);
        $registry = $this->createMock(Registry::class);
        $registry->expects($this->any())->method('registry')->willReturn($isPageNofollow);
        $model = new IsAddNofollow($getConfigProvider, $getSelectedFiltersSettings, $isBrandPage, $registry);

        $actualResult = $model->execute($relNofollow, $followMode);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * Data provider for isNofollowByModeDataProvider test
     * @return array
     */
    public function executeDataProvider()
    {
        return [
            [
                RelNofollow::MODE_NO,
                IndexMode::MODE_NEVER,
                true,
                'test',
                false
            ],
            [
                RelNofollow::MODE_NO,
                IndexMode::MODE_SINGLE_ONLY,
                true,
                'test',
                false
            ],
            [
                RelNofollow::MODE_NO,
                IndexMode::MODE_NEVER,
                false,
                'test',
                false
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_SINGLE_ONLY,
                false,
                'test',
                false
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_SINGLE_ONLY,
                true,
                'nofollow',
                true
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_SINGLE_ONLY,
                true,
                'relnofollow',
                true
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_SINGLE_ONLY,
                false,
                'relnofollow',
                false
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_NEVER,
                true,
                'rel',
                true
            ],
            [
                RelNofollow::MODE_AUTO,
                IndexMode::MODE_ALWAYS,
                true,
                'rel',
                false
            ],
        ];
    }
}
