<?php

namespace Dotsquares\Shopby\Block\Adminhtml;

class Group extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Dotsquares_Shopby';
        $this->_controller = 'adminhtml_group';
        $this->_headerText = __('Manage Group Attributes');
        $this->_addButtonLabel = __('Add New Group');
        parent::_construct();
    }
}
