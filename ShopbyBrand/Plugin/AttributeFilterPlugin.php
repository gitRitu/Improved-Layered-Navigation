<?php

namespace Dotsquares\ShopbyBrand\Plugin;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Dotsquares\ShopbyBrand\Helper\Content;

class AttributeFilterPlugin
{
    /**
     * @var  Content
     */
    protected $contentHelper;

    public function __construct(Content $contentHelper)
    {
        $this->contentHelper = $contentHelper;
    }

    /**
     * @param AbstractFilter $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsVisibleWhenSelected(AbstractFilter $subject, $result)
    {
        return ($result && $this->isBrandingBrand($subject)) ? false : $result;
    }

    /**
     * @param AbstractFilter $subject
     * @param bool $result
     * @return bool
     */
    public function afterShouldAddState(AbstractFilter $subject, $result)
    {
        return ($result && $this->isBrandingBrand($subject)) ? false : $result;
    }

    /**
     * @param AbstractFilter $subject
     * @return bool
     */
    protected function isBrandingBrand(AbstractFilter $subject)
    {
        $brand = $this->contentHelper->getCurrentBranding();
        return $brand && ($subject->getRequestVar() == $brand->getFilterCode());
    }
}
