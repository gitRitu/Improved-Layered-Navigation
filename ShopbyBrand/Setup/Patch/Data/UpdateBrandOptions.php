<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBrand\Setup\Patch\Data;

use Dotsquares\ShopbyBase\Helper\Data as BaseHelper;
use Dotsquares\ShopbyBrand\Model\Brand\OptionsUpdater;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateBrandOptions implements DataPatchInterface
{
    /**
     * @var BaseHelper
     */
    private $baseHelper;

    /**
     * @var OptionsUpdater
     */
    private $optionsUpdater;

    /**
     * @var State
     */
    private $appState;

    public function __construct(
        BaseHelper $baseHelper,
        OptionsUpdater $optionsUpdater,
        State $appState
    ) {
        $this->baseHelper = $baseHelper;
        $this->optionsUpdater = $optionsUpdater;
        $this->appState = $appState;
    }

    public function apply()
    {
        $this->appState->emulateAreaCode(Area::AREA_ADMINHTML, [$this, 'applyCallback'], []);

        return $this;
    }
    
    public function applyCallback()
    {
        if (!$this->baseHelper->isShopbyInstalled()) {
            $this->optionsUpdater->execute();
        }

        return $this;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
