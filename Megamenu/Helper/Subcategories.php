<?php

namespace Dotsquares\Megamenu\Helper;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Subcategories extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CategoryCollectionFactory $categoryCollectionFactory
    )
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSubcategories($category) {
        if (!$category) {
            return null;
        }

        $categoryPath = $category->getPath();
        $categoryLevel = $category->getLevel();

        $subcategoriesCollection = $this->categoryCollectionFactory->create();
        $subcategoriesCollection->addFieldToSelect(['name','url','dotsquares_sc_hide', 'dotsquares_sc_image']);
        $subcategoriesCollection->addAttributeToFilter('is_active', 1);
        $subcategoriesCollection->addAttributeToFilter('path', ['like' => $categoryPath . '%']);
        $subcategoriesCollection->addAttributeToFilter('level', $categoryLevel + 1);
        $subcategoriesCollection->addAttributeToFilter([
            ['attribute' => 'dotsquares_sc_hide', 'null' => true],
            ['attribute' => 'dotsquares_sc_hide', 'eq' => 0]
        ]);
        $subcategoriesCollection->setOrder('position', 'ASC');
        $subcategoriesCollection->load();

        return $subcategoriesCollection;
    }

    /**
     * @param string $subCategoryImage
     * @return string
     */
    public function parseMediaUrl($subCategoryImage)
    {
        return str_replace(['media//media', 'media/media'], ['media'], $subCategoryImage);
    }
}
