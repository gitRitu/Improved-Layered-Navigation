<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Setup\Patch\Data;

use Dotsquares\Shopby\Model\ConfigProvider;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateSliderStyle implements DataPatchInterface
{
    public const TABLE_CORE_CONFIG_DATA = 'core_config_data';

    public const OLD_SLIDER_STYLE_CONFIG_PATH = 'dsshopby/general/slider_style';

    /**
     * @var ConfigInterface
     */
    private $resourceConfig;

    public function __construct(
        ConfigInterface $resourceConfig
    ) {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->resourceConfig->getConnection()->update(
            $this->resourceConfig->getTable(self::TABLE_CORE_CONFIG_DATA),
            ['path' => 'dsshopby/' . ConfigProvider::SLIDER_STYLE],
            ['path' . ' = (?)' => self::OLD_SLIDER_STYLE_CONFIG_PATH]
        );

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
