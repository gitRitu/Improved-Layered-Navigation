<?php

namespace Dotsquares\ShopbyBase\Plugin\Catalog\Block\Category;

use Magento\Catalog\Block\Category\View as CategoryViewBlock;
use Magento\Catalog\Model\Category;
use Dotsquares\ShopbyBase\Model\Customizer\CategoryFactory as CustomizerCategoryFactory;
use Dotsquares\ShopbyBase\Model\Category\Manager as CategoryManager;

/**
 * Class View
 */
class View
{
    /**
     * @var CustomizerCategoryFactory
     */
    private $customizerCategoryFactory;

    /**
     * @var bool
     */
    private $categoryModified = false;

    /**
     * @param CustomizerCategoryFactory $customizerCategoryFactory
     */
    public function __construct(
        CustomizerCategoryFactory $customizerCategoryFactory
    ) {
        $this->customizerCategoryFactory = $customizerCategoryFactory;
    }

    /**
     * @param CategoryViewBlock $subject
     * @param Category $category
     * @return Category
     */
    public function afterGetCurrentCategory(CategoryViewBlock $subject, $category)
    {
        if ($category instanceof Category && !$this->categoryModified) {
            $this->customizerCategoryFactory->create()
                ->prepareData($category);

            $this->categoryModified = true;
        }
        return $category;
    }

    /**
     * @param CategoryViewBlock $subject
     * @param bool $isMixedMode
     * @return bool
     */
    public function afterIsMixedMode(CategoryViewBlock $subject, $isMixedMode)
    {
        if (!$isMixedMode) {
            if ($subject->getCurrentCategory()->getData(CategoryManager::CATEGORY_FORCE_MIXED_MODE)) {
                $isMixedMode = true;
            }
        }

        return $isMixedMode;
    }
}
