<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBrand\Observer\Admin;

use Dotsquares\ShopbyBrand\Model\Brand\OptionsUpdater;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ConfigChanged implements ObserverInterface
{
    /**
     * @var OptionsUpdater
     */
    private $optionsUpdater;

    public function __construct(OptionsUpdater $optionsUpdater)
    {
        $this->optionsUpdater = $optionsUpdater;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $this->optionsUpdater->execute();
    }
}
