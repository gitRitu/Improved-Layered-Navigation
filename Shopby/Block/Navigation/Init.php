<?php

namespace Dotsquares\Shopby\Block\Navigation;

use Magento\Framework\View\Element\Template;

class Init extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        Template\Context $context,
        \Dotsquares\ShopbyBase\Model\Category\Manager $categoryManager,
        array $data = []
    ) {
        $categoryManager->init();
        parent::__construct($context, $data);
    }
}
