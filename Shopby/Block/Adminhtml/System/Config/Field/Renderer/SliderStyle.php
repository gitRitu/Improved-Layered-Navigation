<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Block\Adminhtml\System\Config\Field\Renderer;

use Magento\Framework\View\Element\Template;

class SliderStyle extends Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Dotsquares_Shopby::system/config/field/style.phtml');
    }
}
