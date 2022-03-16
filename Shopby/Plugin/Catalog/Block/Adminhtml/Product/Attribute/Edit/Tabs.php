<?php

namespace Dotsquares\Shopby\Plugin\Catalog\Block\Adminhtml\Product\Attribute\Edit;

use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tabs as MagentoAttributeEditTabs;

class Tabs
{
    /**
     * @param MagentoAttributeEditTabs $subject
     * @return array
     */
    public function beforeToHtml(MagentoAttributeEditTabs $subject)
    {
        $content = $subject->getRequest()->getParam('attribute_id') ? $subject->getChildHtml('dsshopby') : null;
        /*disable for new products because wrong loading dispay mode */
        $subject->addTabAfter(
            'dotsquares_shopby',
            [
                'label' => __('Improved Layered Navigation'),
                'title' => __('Improved Layered Navigation'),
                'content' => $content,
            ],
            'front'
        );

        return [];
    }
}