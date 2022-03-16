<?php

namespace Dotsquares\Shopby\Plugin\Cms\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Dotsquares\Shopby\Model\Cms\Page as DotsquaresCmsPage;

class Page
{
    /**
     * @var \Dotsquares\Shopby\Model\Cms\PageFactory
     */
    private $pageFactory;

    /**
     * @var \Dotsquares\Shopby\Api\CmsPageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var array
     */
    private $pageData = [];

    /**
     * Page constructor.
     * @param \Dotsquares\Shopby\Model\Cms\PageFactory $pageFactory
     * @param \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $cmsPageRepository
     */
    public function __construct(
        \Dotsquares\Shopby\Model\Cms\PageFactory $pageFactory,
        \Dotsquares\Shopby\Api\CmsPageRepositoryInterface $cmsPageRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $cmsPageRepository;
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param \Closure $proceed
     * @param string $key
     * @param null $index
     * @return mixed
     */
    public function aroundGetData(
        \Magento\Cms\Model\Page $page,
        \Closure $proceed,
        $key = '',
        $index = null
    ) {
        $data = $proceed($key, $index);
        if ($this->isAddDotsquaresPageData($page, $key, $data)) {
            $data[DotsquaresCmsPage::VAR_SETTINGS] = $this->getDotsquaresPageData($page->getId());
        }

        return $data;
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    private function isAddDotsquaresPageData(\Magento\Cms\Model\Page $page, $key, $data)
    {
        $isPageDataNeeded = $key === '' || $key === DotsquaresCmsPage::VAR_SETTINGS;
        $isFirstCall = !(is_array($data) && array_key_exists(DotsquaresCmsPage::VAR_SETTINGS, $data));
        return $isPageDataNeeded && $isFirstCall && $page->getId();
    }

    /**
     * @param int $pageId
     * @return array
     */
    private function getDotsquaresPageData($pageId)
    {
        if (!array_key_exists($pageId, $this->pageData)) {
            $this->pageData[$pageId] = [];
            try {
                $shopbyPage = $this->pageRepository->getByPageId($pageId);
                if ($shopbyPage->getId()) {
                    $this->pageData[$pageId] = $shopbyPage->getData();
                }
            } catch (NoSuchEntityException $e) {
                return $this->pageData[$pageId];
            }
        }

        return $this->pageData[$pageId];
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param \Magento\Cms\Model\Page $returnPage
     * @return \Magento\Cms\Model\Page
     */
    public function afterSave(
        \Magento\Cms\Model\Page $page,
        \Magento\Cms\Model\Page $returnPage
    ) {
        if (!$page instanceof \Amasty\Xlanding\Model\Page && $page->getId()) {
            $settings = $page->getData(DotsquaresCmsPage::VAR_SETTINGS);
            if (\is_array($settings)) {
                try {
                    $shopbyPage = $this->pageRepository->getByPageId((int) $page->getId());
                } catch (NoSuchEntityException $e) {
                    $shopbyPage = $this->pageFactory->create();
                }
                $shopbyPage->setPageId((int) $page->getId());
                $shopbyPage->addData($settings);
                $this->pageRepository->save($shopbyPage);
            }
        }

        return $returnPage;
    }
}
