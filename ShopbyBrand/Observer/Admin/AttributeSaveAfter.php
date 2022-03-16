<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBrand\Observer\Admin;

use Dotsquares\ShopbyBrand\Helper\Data as BrandHelper;
use Dotsquares\ShopbyBrand\Model\Brand\OptionsUpdater;
use Dotsquares\ShopbyBrand\Model\ConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AttributeSaveAfter implements ObserverInterface
{
    /**
     * @var OptionsUpdater
     */
    private $optionsUpdater;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        OptionsUpdater $optionsUpdater,
        ConfigProvider $configProvider
    ) {
        $this->optionsUpdater = $optionsUpdater;
        $this->configProvider = $configProvider;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        if (in_array(
            $observer->getEvent()->getAttribute()->getAttributeCode(),
            $this->configProvider->getAllBrandAttributeCodes()
        )) {
            $this->optionsUpdater->execute(
                $observer->getEvent()->getAttribute()->getAttributeCode()
            );
        }
    }
}
