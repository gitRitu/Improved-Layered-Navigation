<?php

namespace Dotsquares\Shopby\Model\ResourceModel\Cms\Relation;

use Dotsquares\Shopby\Model\Cms\Page;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Cms Page extension SaveHandler.
 * Save additional settings of CMS Page.
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var \Dotsquares\Shopby\Api\CmsPageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \Dotsquares\Shopby\Model\Cms\PageFactory
     */
    protected $pageFactory;

    /**
     * SaveHandler constructor.
     *
     * @param \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $cmsPageRepository
     * @param \Dotsquares\Shopby\Model\Cms\PageFactory $factory
     */
    public function __construct(
        \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $cmsPageRepository,
        \Dotsquares\Shopby\Model\Cms\PageFactory $factory
    ) {
        $this->pageRepository = $cmsPageRepository;
        $this->pageFactory = $factory;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        $settings = $entity->getData(Page::VAR_SETTINGS);

        if (\is_array($settings) && $entity->getId()) {
            try {
                $shopbyPage = $this->pageRepository->getByPageId((int) $entity->getId());
            } catch (NoSuchEntityException $e) {
                $shopbyPage = $this->pageFactory->create();
            }
            $shopbyPage->setPageId((int) $entity->getId());
            $shopbyPage->addData($settings);
            $this->pageRepository->save($shopbyPage);
        }

        return $entity;
    }
}
