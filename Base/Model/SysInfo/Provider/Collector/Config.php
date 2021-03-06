<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Provider\Collector;

use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory as ConfigCollectionFactory;

class Config implements CollectorInterface
{
    public const CONFIG_PATH_KEY = 'path';
    public const CONFIG_VALUE_KEY = 'value';

    /**
     * @var ConfigCollectionFactory
     */
    private $configCollectionFactory;

    public function __construct(
        ConfigCollectionFactory $configCollectionFactory
    ) {
        $this->configCollectionFactory = $configCollectionFactory;
    }

    public function get(): array
    {
        $configData = [];

        $configCollection = $this->configCollectionFactory->create()
            ->addFieldToSelect([self::CONFIG_PATH_KEY, self::CONFIG_VALUE_KEY]);

        foreach ($this->getPathConditions() as $condition) {
            $configCollection->addFieldToFilter(self::CONFIG_PATH_KEY, $condition);
        }

        foreach ($configCollection->getData() as $config) {
            $path = $this->preparePath($config[self::CONFIG_PATH_KEY]);
            $configData[$path] = $config[self::CONFIG_VALUE_KEY];
        }

        return $configData;
    }

    protected function getPathConditions(): array
    {
        return [
            ['like' => 'am%'],
            ['nlike' => '%token%']
        ];
    }

    private function preparePath(string $path): string
    {
        return str_replace('/', '_', $path);
    }
}
