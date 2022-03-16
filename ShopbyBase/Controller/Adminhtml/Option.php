<?php

namespace Dotsquares\ShopbyBase\Controller\Adminhtml;

/**
 * Class Option
 */
abstract class Option extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dotsquares_ShopbyBase::option');
    }
}
