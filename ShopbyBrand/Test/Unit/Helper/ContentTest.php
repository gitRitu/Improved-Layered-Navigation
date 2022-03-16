<?php

namespace Dotsquares\ShopByBrand\Test\Unit\Helper;

use Dotsquares\ShopbyBrand\Helper\Content;
use Dotsquares\ShopbyBrand\Test\Unit\Traits;

/**
 * Class BrandsPopupTest
 *
 * @see BrandsPopup
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class ContentTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    public const CHECK_ROOT_CATEGORY_VALUE = true;

    public const BRAND_VALUE = "1";

    public const BRAND_ATTRIBUTE_CODE = 1;

    /**
     * @covers Content::getCurrentBranding
     */
    public function testGetCurrentBranding()
    {
        $curBranding = $this->getObjectManager()->getObject(\Dotsquares\ShopbyBase\Model\OptionSetting::class);

        $content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->setMethods(['checkRootCategory'])
            ->getMock();
        $content->expects($this->any())->method('checkRootCategory')
            ->will($this->returnValue(self::CHECK_ROOT_CATEGORY_VALUE));

        $helper = $this->getMockBuilder(\Dotsquares\ShopbyBrand\Helper\Data::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBrandAttributeCode'])
            ->getMock();
        $helper->expects($this->any())->method('getBrandAttributeCode')
            ->will($this->returnValue(self::BRAND_ATTRIBUTE_CODE));

        $request = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getControllerName', 'getParam'])
            ->getMock();
        $request->expects($this->any())->method('getControllerName')
            ->will($this->returnValue('index'));
        $request->expects($this->any())->method('getParam')
            ->will($this->returnValue(self::BRAND_VALUE));

        $store = $this->getObjectManager()->getObject(\Magento\Store\Model\Store::class);
        $store->setData('store_id', 0);

        $storeManager = $this->createMock(\Magento\Store\Model\StoreManager::class);
        $storeManager->expects($this->any())->method('getStore')->will($this->returnValue($store));

        $optionHelper = $this->createMock(\Dotsquares\ShopbyBase\Helper\OptionSetting::class);
        $optionHelper->expects($this->any())->method('getSettingByValue')
            ->will($this->returnValue($curBranding));

        $this->setProperty($content, 'helper', $helper, Content::class);
        $this->setProperty($content, '_request', $request);
        $this->setProperty($content, 'optionHelper', $optionHelper, Content::class);
        $this->setProperty($content, 'storeManager', $storeManager, Content::class);

        $this->assertInstanceOf(
            \Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface::class,
            $content->getCurrentBranding(),
            'Initialization of branding failed'
        );
        $this->assertInstanceOf(
            \Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface::class,
            $content->getCurrentBranding(),
            'Getting of branding failed'
        );
    }
}