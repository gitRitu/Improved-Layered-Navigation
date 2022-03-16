<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Plugin\Magento\LayeredNavigationStaging\Block\Navigation;

use Dotsquares\Shopby\Model\Layer\GetFiltersExpanded;
use Magento\LayeredNavigationStaging\Block\Navigation;

class ExpandFilterOptionsPlugin
{
    public const SEARCH = 'data-role="collapsible"';
    public const REPLACE = 'data-role="collapsible" data-collapsible="true"';

    /**
     * @var GetFiltersExpanded
     */
    private $getFiltersExpanded;

    public function __construct(
        GetFiltersExpanded $getFiltersExpanded
    ) {
        $this->getFiltersExpanded = $getFiltersExpanded;
    }

    public function afterToHtml(
        Navigation $subject,
        string $html
    ): string {
        $expanded = $this->getFiltersExpanded->execute();
        $search = self::SEARCH;
        $replace = self::REPLACE;
        $counter = 0;
        $html = preg_replace_callback(
            sprintf('/%s/', $search),
            function () use ($search, $replace, &$counter, $expanded) {
                return in_array($counter++, $expanded) ? $replace : $search;
            },
            $html
        );

        return $html;
    }
}
