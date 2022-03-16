<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Controller\Adminhtml;

use Dotsquares\GroupedOptions\Model\Backend\Group\Registry as GroupRegistry;
use Magento\Framework\App\Cache\TypeListInterface;

abstract class Group extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Dotsquares_GroupedOptions::group_options';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Dotsquares\GroupedOptions\Model\GroupAttrFactory
     */
    protected $groupAttrFactory;

    /**
     * @var \Dotsquares\GroupedOptions\Api\Data\GroupAttrRepositoryInterface
     */
    protected $groupAttrRepository;

    /**
     * @var \Magento\Backend\Model\SessionFactory
     */
    protected $sessionFactory;

    /**
     * @var  TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var GroupRegistry
     */
    private $groupRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        GroupRegistry $groupRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dotsquares\GroupedOptions\Model\GroupAttrFactory $groupAttrFactory,
        \Dotsquares\GroupedOptions\Api\Data\GroupAttrRepositoryInterface $groupAttrRepository,
        \Magento\Backend\Model\SessionFactory $sessionFactory,
        TypeListInterface $typeList
    ) {
        $this->groupRegistry = $groupRegistry;
        $this->groupAttrFactory = $groupAttrFactory;
        $this->groupAttrRepository = $groupAttrRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->sessionFactory = $sessionFactory;
        $this->cacheTypeList = $typeList;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dotsquares_GroupedOptions::group_options');
    }

    protected function getGroupRegistry(): GroupRegistry
    {
        return $this->groupRegistry;
    }
}
