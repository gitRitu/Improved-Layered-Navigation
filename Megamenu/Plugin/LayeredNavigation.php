<?php
namespace Dotsquares\Megamenu\Plugin;
use Dotsquares\Megamenu\Model\Attribute\Source\CategoryLayout;

class LayeredNavigation
{
    /**
     * @param \Magento\LayeredNavigation\Block\Navigation $subject
     * @param bool $result
     * @return bool
     */
    public function afterCanShowBlock(\Magento\LayeredNavigation\Block\Navigation $subject, $result)
    {
        $category = $subject->getLayer()->getCurrentCategory();
        $subcategoriesLayout = $category->getData('dotsquares_sc_layout');
        if ($subcategoriesLayout == CategoryLayout::LAYOUT_IMAGES) {
            $result = false;
        }
        return $result;
    }
}
