<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Controller\Adminhtml\Group;

class Index extends \Dotsquares\GroupedOptions\Controller\Adminhtml\Group
{
    const ADMIN_RESOURCE = 'Dotsquares_GroupedOptions::group_options';

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dotsquares_GroupedOptions::group_options')
            ->addBreadcrumb(__('Manage Grouped Options'), __('Manage Grouped Options'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Grouped Options'));

        return $resultPage;
    }
}
