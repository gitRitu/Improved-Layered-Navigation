<?php

namespace Dotsquares\Shopby\Block\Navigation\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\Store;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;

class SearchForm extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'layer/widget/search-form.phtml';
}
