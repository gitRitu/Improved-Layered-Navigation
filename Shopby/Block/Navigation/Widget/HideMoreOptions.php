<?php

namespace Dotsquares\Shopby\Block\Navigation\Widget;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;

class HideMoreOptions extends \Magento\Framework\View\Element\Template implements WidgetInterface
{
    /**
     * @var FilterSettingInterface
     */
    private $filterSetting;

    /**
     * @var string
     */
    protected $_template = 'Dotsquares_Shopby::layer/widget/hide_more_options.phtml';

    /**
     * @param FilterSettingInterface $filterSetting
     * @return $this
     */
    public function setFilterSetting(FilterSettingInterface $filterSetting)
    {
        $this->filterSetting = $filterSetting;
        return $this;
    }

    /**
     * @return FilterSettingInterface
     */
    public function getFilterSetting()
    {
        return $this->filterSetting;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return ($this->getIsState() && $this->getUnfoldedOptions()) || $this->filterSetting->getNumberUnfoldedOptions()
            ? parent::toHtml()
            : '';
    }
}
