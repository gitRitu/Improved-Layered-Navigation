<?php
namespace Dotsquares\Shopby\Block\Navigation;

interface RendererInterface
{
    public function collectFilters();

    public function getFilter();
}
