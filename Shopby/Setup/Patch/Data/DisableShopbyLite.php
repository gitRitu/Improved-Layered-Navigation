<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\Manager;
use Magento\Framework\Module\Status;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class DisableShopbyLite implements DataPatchInterface
{
    /**
     * @var Status
     */
    private $moduleStatus;

    /**
     * @var Manager
     */
    private $moduleManager;

    public function __construct(
        Status $moduleStatus,
        Manager $moduleManager
    ) {
        $this->moduleStatus = $moduleStatus;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function apply()
    {
        if ($this->moduleManager->isEnabled('Dotsquares_ShopbyLite')) {
            try {
                $this->moduleStatus->setIsEnabled(false, ['Dotsquares_ShopbyLite']);
            } catch (\Exception $e) {
                throw new LocalizedException(
                    __('Please disable Dotsquares_ShopbyLite module manually.')
                );
            }
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
