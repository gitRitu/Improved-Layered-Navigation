<?php

namespace Dotsquares\Shopby\Model\Source\FilterDataPosition;

use Dotsquares\Shopby\Model\Source;

class MetaDescription extends Source\AbstractFilterDataPosition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return mixed|void
     */
    protected function _setLabel()
    {
        $this->_label = __('Meta-Description');
    }
}
