<?php

namespace Dotsquares\ShopbyBrand\Helper;

use Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface;
use Dotsquares\ShopbyBase\Helper\OptionSetting;
use Magento\Catalog\Model\Layer;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManager;

class Content extends AbstractHelper
{
    public const CATEGORY_FORCE_MIXED_MODE = 'dsshopby_force_mixed_mode';

    /**
     * @var  Layer\Resolver
     */
    private $layerResolver;

    /**
     * @var  OptionSetting
     */
    private $optionHelper;

    /**
     * @var  StoreManager
     */
    private $storeManager;

    /**
     * @var OptionSettingInterface
     */
    private $currentBranding;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        Context $context,
        Layer\Resolver $layerResolver,
        OptionSetting $optionHelper,
        StoreManager $storeManager,
        Data $helper
    ) {
        parent::__construct($context);
        $this->layerResolver = $layerResolver;
        $this->optionHelper = $optionHelper;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Get current Brand.
     * @return null|OptionSettingInterface
     */
    public function getCurrentBranding()
    {
        if (!$this->currentBranding) {
            if ($this->checkControllerName() &&
                $this->helper->getBrandAttributeCode() &&
                ($brandValue = $this->getBrandValue()) &&
                $this->checkRootCategory()
            ) {
                $this->loadSetting($brandValue);
            } else {
                $this->currentBranding = null;
            }
        }

        return $this->currentBranding;
    }

    /**
     * @param $brandValue
     */
    private function loadSetting($brandValue)
    {
        $this->currentBranding = $this->optionHelper->getSettingByValue(
            $brandValue,
            $this->helper->getBrandAttributeCode(),
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    private function checkControllerName()
    {
        return $this->_request->getControllerName() === 'index';
    }

    /**
     * @return mixed
     */
    private function getBrandValue()
    {
        return $this->_request->getParam($this->helper->getBrandAttributeCode());
    }

    /**
     * @return bool
     */
    protected function checkRootCategory()
    {
        $layer = $this->layerResolver->get();

        return $layer->getCurrentCategory()->getId() ==
            $layer->getCurrentStore()->getRootCategoryId();
    }
}