<?php

namespace Dotsquares\Shopby\Plugin\Cms\Helper;

use Magento\Framework\App\Action\Action;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Page
{
    public const LAYER_CMS = 'dsshopby_cms';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \Dotsquares\Shopby\Api\CmsPageRepositoryInterface
     */
    protected $shopbyPageRepository;

    /**
     * @var \Dotsquares\Shopby\Model\Cms\PageFactory
     */
    protected $shopbyPageFactory;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * CmsPageHelperPlugin constructor.
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $shopbyPageRepository
     * @param \Dotsquares\Shopby\Model\Cms\PageFactory $shopbyPageFactory
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $shopbyPageRepository,
        \Dotsquares\Shopby\Model\Cms\PageFactory $shopbyPageFactory,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->shopbyPageRepository = $shopbyPageRepository;
        $this->shopbyPageFactory = $shopbyPageFactory;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @param \Magento\Cms\Helper\Page $helper
     * @param Action $action
     * @param int|string|null $pageId
     * @return array|void
     * @SuppressWarnings(PHPMD.UnusedFormatParameter)
     */
    public function beforePrepareResultPage(
        \Magento\Cms\Helper\Page $helper,
        Action $action,
        $pageId = null
    ) {
        if ($pageId !== null) {
            $delimiterPosition = strrpos($pageId, '|');
            if ($delimiterPosition) {
                $pageId = substr($pageId, 0, $delimiterPosition);
            }
        }

        try {
            $shopbyPage = $this->shopbyPageRepository->getByPageId($pageId);
        } catch (NoSuchEntityException $e) {
            return;
        }

        if ($shopbyPage->getEnabled()) {
            $this->layerResolver->create(self::LAYER_CMS);
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addHandle('dshopby_cms_navigation');
        }
    }
}
