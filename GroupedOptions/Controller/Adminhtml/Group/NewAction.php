<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Controller\Adminhtml\Group;

use Dotsquares\GroupedOptions\Model\Backend\Group\Registry as GroupRegistry;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\Forward;

class NewAction extends \Dotsquares\GroupedOptions\Controller\Adminhtml\Group
{
    const ADMIN_RESOURCE = 'Dotsquares_GroupedOptions::group_options';

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        GroupRegistry $groupRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dotsquares\GroupedOptions\Model\GroupAttrFactory $groupAttrFactory,
        \Dotsquares\GroupedOptions\Api\Data\GroupAttrRepositoryInterface $groupAttrRepository,
        \Magento\Backend\Model\SessionFactory $sessionFactory,
        TypeListInterface $typeList,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct(
            $context,
            $groupRegistry,
            $resultPageFactory,
            $groupAttrFactory,
            $groupAttrRepository,
            $sessionFactory,
            $typeList
        );
    }

    /**
     * @return Forward
     */
    public function execute()
    {
        /** @var Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
