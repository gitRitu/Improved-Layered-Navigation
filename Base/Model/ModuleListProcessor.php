<?php

namespace Dotsquares\Base\Model;

use Dotsquares\Base\Model\Feed\ExtensionsProvider;
use Magento\Framework\Module\ModuleListInterface;

class ModuleListProcessor
{
    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var array
     */
    private $modules;

    /**
     * @var Feed\ExtensionsProvider
     */
    private $extensionsProvider;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    public function __construct(
        ModuleListInterface $moduleList,
        ExtensionsProvider $extensionsProvider,
        ModuleInfoProvider $moduleInfoProvider
    ) {
        $this->moduleList = $moduleList;
        $this->extensionsProvider = $extensionsProvider;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * @return array
     */
    public function getModuleList()
    {
        if ($this->modules !== null) {
            return $this->modules;
        }

        $this->modules = [
            'lastVersion' => [],
            'hasUpdate' => []
        ];

        $modules = $this->moduleList->getNames();
        sort($modules);

        foreach ($modules as $moduleName) {
            if ($moduleName === 'Dotsquares_Base'
                || strpos($moduleName, 'Dotsquares_') === false
                || in_array($moduleName, $this->moduleInfoProvider->getRestrictedModules(), true)
            ) {
                continue;
            }

            try {
                if (!is_array($module = $this->getModuleInfo($moduleName))) {
                    continue;
                }
            } catch (\Exception $e) {
                continue;
            }

            if (empty($module['hasUpdate'])) {
                $this->modules['lastVersion'][] = $module;
            } else {
                $this->modules['hasUpdate'][] = $module;
            }
        }

        return $this->modules;
    }

    /**
     * @param string $moduleCode
     * @return array|mixed|string
     */
    protected function getModuleInfo($moduleCode)
    {
        $module = $this->moduleInfoProvider->getModuleInfo($moduleCode);

        if (!is_array($module)
            || !isset($module['version'])
            || !isset($module['description'])
        ) {
            return '';
        }

        $currentVer = $module['version'];
        $module['description'] = $this->replaceDotsquaresText($module['description']);

        $allExtensions = $this->extensionsProvider->getAllFeedExtensions();
        if ($allExtensions && isset($allExtensions[$moduleCode])) {
            $ext = end($allExtensions[$moduleCode]);

            $lastVer = $ext['version'];
            $module['lastVersion'] = $lastVer;
            $module['hasUpdate'] = version_compare($currentVer, $lastVer, '<');
            $module['description'] = $this->replaceDotsquaresText($ext['name']);
            $module['url'] = !empty($ext['url']) ? $ext['url'] : '';
            $module['date'] = !empty($ext['date']) ? $ext['date'] : '';

            return $module;
        }

        return '';
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function replaceDotsquaresText($moduleName)
    {
        return str_replace(['for Magento 2', 'by Dotsquares'], '', $moduleName);
    }
}
