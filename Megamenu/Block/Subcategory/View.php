<?php

namespace Dotsquares\Megamenu\Block\Subcategory;

class View extends \Magento\Catalog\Block\Category\View
{
    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = parent::getIdentities();
        $currentCategory = $this->getCurrentCategory();
        $subcategoriesLayout = $currentCategory->getData('dotsquares_sc_layout');

        if ($subcategoriesLayout == \Dotsquares\Megamenu\Model\Attribute\Source\CategoryLayout::LAYOUT_IMAGES) {
            $childCategories = $currentCategory->getChildrenCategories();
            foreach ($childCategories as $category) {
                $identities = array_merge($identities, $category->getIdentities());
            }
        }
        return $identities;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl() {
        return str_replace(['/pub/media'], [''], $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA));
    }
}
