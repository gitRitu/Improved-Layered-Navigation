<?php

namespace Dotsquares\ShopbySeo\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Action;

class FrontControllerActionPredispatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var array
     */
    private $suffixModules = ['catalog', 'dsshopby', 'dsbrand'];

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    private $actionFlag;

    /**
     * @var \Dotsquares\ShopbySeo\Helper\Url
     */
    private $urlHelper;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Dotsquares\ShopbySeo\Helper\Url $urlHelper
    ) {
        $this->request = $request;
        $this->actionFlag = $actionFlag;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->request->getMetaData(\Dotsquares\ShopbySeo\Helper\Data::SEO_REDIRECT_FLAG)
            && $this->request->getModuleName()
        ) {
            $this->request->setDispatched(true);
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        } elseif ($this->request->getMetaData(\Dotsquares\ShopbySeo\Helper\Data::SEO_REDIRECT_MISSED_SUFFIX_FLAG)
            && $this->urlHelper->isAddSuffixToShopby()
            && in_array($this->request->getModuleName(), $this->suffixModules)
        ) {
            $this->request->setMetaData(\Dotsquares\ShopbySeo\Helper\Data::SEO_REDIRECT_FLAG, true);
            $this->request->setDispatched(true);
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        }
        return $this;
    }
}
