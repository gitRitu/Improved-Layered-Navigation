<?php
namespace Dotsquares\Megamenu\Plugin;

class PageConfigStructure {

    /**
     * @var \Dotsquares\Megamenu\Helper\Data
     */
    protected $_navigationHelper;

    /**
     * @var \Dotsquares\Megamenu\Helper\Utility
     */
    protected $utilityHelper;

    /**
     * PageConfigStructure constructor.
     * @param \Dotsquares\Megamenu\Helper\Data $navigationHelper
     * @param \Dotsquares\Megamenu\Helper\Utility $utilityHelper
     */
    public function __construct(
        \Dotsquares\Megamenu\Helper\Data $navigationHelper
    ) {
        $this->_navigationHelper = $navigationHelper;
        
    }

    /**
     * Modify the hardcoded breakpoint for styles-menu.css
     * @param \Magento\Framework\View\Page\Config\Structure $subject
     * @param string $name
     * @param array $attributes
     * @return $this
     */
    public function beforeAddAssets(
        \Magento\Framework\View\Page\Config\Structure
        $subject, $name, $attributes
    )
    {
        $widthThreshold = $this->_navigationHelper->getWidthThreshold();
        $mobileBreakPoint  = $widthThreshold . 'px';
        $desktopBreakPoint  = $widthThreshold + 1 . 'px';

        switch ($name) {
            case 'Dotsquares_Megamenu::css/navigation_mobile.css':
                $attributes['media'] = 'screen and (max-width: ' . $mobileBreakPoint . ')';
                if (!$this->_navigationHelper->isEnabled()) {
                    $subject->removeAssets($name);
                }
                break;

            case 'Dotsquares_Megamenu::css/navigation_desktop.css':
                $attributes['media'] = 'screen and (min-width: ' . $desktopBreakPoint . ')';
                if (!$this->_navigationHelper->isEnabled()) {
                    $subject->removeAssets($name);
                }
                break;
        }

        return [$name, $attributes];
    }
}