<?php

namespace Dotsquares\Shopby\Model\Customizer\Category;

use Dotsquares\ShopbyBase\Api\CategoryDataSetterInterface;
use Magento\Catalog\Model\Category;
use Dotsquares\ShopbyBase\Model\Customizer\Category\CustomizerInterface;

class Filter implements CustomizerInterface
{
    /**
     * @var CustomizerInterface
     */
    protected $_contentHelper;

    /**
     * @param CategoryDataSetterInterface $contentHelper
     */
    public function __construct(CategoryDataSetterInterface $contentHelper)
    {
        $this->_contentHelper = $contentHelper;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function prepareData(Category $category)
    {
        $this->_contentHelper->setCategoryData($category);
        return $this;
    }
}
