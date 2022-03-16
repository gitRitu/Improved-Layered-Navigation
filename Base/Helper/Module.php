<?php

namespace Dotsquares\Base\Helper;

use Dotsquares\Base\Model\Feed\ExtensionsProvider;
use Dotsquares\Base\Model\LinkValidator;
use Dotsquares\Base\Model\ModuleInfoProvider;

/**
 * @deprecated Class for backward compatibility. Will be removed someday
 * @see ExtensionsProvider, LinkValidator, ModuleInfoProvider
 */
class Module
{
    /**
     * @var ExtensionsProvider
     */
    private $extensionsProvider;

    /**
     * @var LinkValidator
     */
    private $linkValidator;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    public function __construct(
        ExtensionsProvider $extensionsProvider,
        LinkValidator $linkValidator,
        ModuleInfoProvider $moduleInfoProvider
    ) {
        $this->extensionsProvider = $extensionsProvider;
        $this->linkValidator = $linkValidator;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\Feed\ExtensionsProvider::getAllFeedExtensions
     */
    public function getAllExtensions()
    {
        return $this->extensionsProvider->getAllFeedExtensions();
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\Feed\ExtensionsProvider::getFeedModuleData()
     */
    public function getFeedModuleData($moduleCode)
    {
        return $this->extensionsProvider->getFeedModuleData($moduleCode);
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\ModuleInfoProvider::getRestrictedModules
     */
    public function getRestrictedModules()
    {
        return $this->moduleInfoProvider->getRestrictedModules();
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\ModuleInfoProvider::getModuleInfo
     */
    public function getModuleInfo($moduleCode)
    {
        return $this->moduleInfoProvider->getModuleInfo($moduleCode);
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\ModuleInfoProvider::isOriginMarketplace
     */
    public function isOriginMarketplace($moduleCode = 'Dotsquares_Base')
    {
        return $this->moduleInfoProvider->isOriginMarketplace($moduleCode);
    }

    /**
     * @deprecated since 1.10.2
     * @see \Dotsquares\Base\Model\LinkValidator::validate
     */
    public function validateLink($link)
    {
        return $this->linkValidator->validate($link);
    }
}
