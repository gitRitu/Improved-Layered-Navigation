<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Block\Adminhtml\System\Config\Field;

use Dotsquares\Shopby\Block\Adminhtml\System\Config\Field\Renderer\SliderStyle as SliderStyleRenderer;
use Magento\Config\Block\System\Config\Form\Field;

class SliderStyle extends Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $result = false;
        $renderer = $this->getLayout()->createBlock(SliderStyleRenderer::class);

        if ($renderer) {
            $result = $renderer->toHtml();
        }

        return $result;
    }
}
