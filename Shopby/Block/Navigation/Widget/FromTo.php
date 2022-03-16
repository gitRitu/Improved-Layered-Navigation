<?php

namespace Dotsquares\Shopby\Block\Navigation\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;

class FromTo extends \Magento\Framework\View\Element\Template implements WidgetInterface
{
    /**
     * @var \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    private $filterSetting;

    /**
     * @var string
     */
    protected $_template = 'layer/widget/fromto.phtml';

    /**
     * @var \Dotsquares\Shopby\Helper\Data
     */
    private $helper;

    /**
     * @var string
     */
    private $type;

    /**
     * @var  FilterInterface
     */
    private $filter;

    public function __construct(
        Template\Context $context,
        \Dotsquares\Shopby\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @param \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     * @return $this
     */
    public function setFilterSetting(\Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting)
    {
        $this->filterSetting = $filterSetting;
        return $this;
    }

    /**
     * @return string
     */
    public function collectFilters()
    {
        return $this->helper->collectFilters();
    }

    /**
     * @return \Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting()
    {
        return $this->filterSetting;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setWidgetType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidgetType()
    {
        return $this->type;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
