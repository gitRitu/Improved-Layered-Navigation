<?php

namespace Dotsquares\ShopbyBase\Controller\Adminhtml\Option;

/**
 * Class Settings
 */
class Settings extends \Dotsquares\ShopbyBase\Controller\Adminhtml\Option
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }
}
