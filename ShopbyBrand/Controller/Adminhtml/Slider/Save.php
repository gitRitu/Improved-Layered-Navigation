<?php

namespace Dotsquares\ShopbyBrand\Controller\Adminhtml\Slider;

class Save extends \Dotsquares\ShopbyBase\Controller\Adminhtml\Option\Save
{
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dotsquares_ShopbyBrand::slider');
    }

    protected function _redirectRefer()
    {
        //phpcs:ignore Magento2.Legacy.ObsoleteResponse.ForwardResponseMethodFound
        $this->_forward('index');
    }
}
