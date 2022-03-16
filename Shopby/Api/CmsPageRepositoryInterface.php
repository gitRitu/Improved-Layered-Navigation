<?php
namespace Dotsquares\Shopby\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface CmsPageRepositoryInterface
{
    public const TABLE = 'dotsquares_dsshopby_cms_page';

    /**
     * @param int $pageId
     * @return \Dotsquares\Shopby\Model\Cms\Page
     * @throws NoSuchEntityException
     */
    public function get($pageId);

    /**
     * @param int $pageId
     * @return \Dotsquares\Shopby\Model\Cms\Page
     * @throws NoSuchEntityException
     */
    public function getByPageId($pageId);

    /**
     * @param \Dotsquares\Shopby\Model\Cms\Page $page
     * @return \Dotsquares\Shopby\Model\Cms\Page
     */
    public function save($page);
}
