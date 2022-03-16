<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Provider\Collector\LicenceService;

use Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo\Module as RequestModule;
use Dotsquares\Base\Model\ModuleInfoProvider;
use Dotsquares\Base\Model\SysInfo\Provider\Collector\CollectorInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;

class Module implements CollectorInterface
{
    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    public function __construct(
        ModuleInfoProvider $moduleInfoProvider,
        DeploymentConfig $deploymentConfig
    ) {
        $this->moduleInfoProvider = $moduleInfoProvider;
        $this->deploymentConfig = $deploymentConfig;
    }

    public function get(): array
    {
        $modulesData = [];
        $moduleList = $this->deploymentConfig->get(ConfigOptionsListConstants::KEY_MODULES);
        foreach ($moduleList as $moduleName => $moduleStatus) {
            if (strpos($moduleName, 'Dotsquares_') === 0) {
                $moduleInfo = $this->moduleInfoProvider->getModuleInfo($moduleName);
                $modulesData[] = [
                    RequestModule::CODE => $moduleName,
                    RequestModule::VERSION => $moduleInfo[ModuleInfoProvider::MODULE_VERSION_KEY] ?? '',
                    RequestModule::STATUS  => (bool)$moduleStatus
                ];
            }
        }

        return $modulesData;
    }
}
