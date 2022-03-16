<?php

declare(strict_types=1);

namespace Dotsquares\Base\Plugin\Framework\View\TemplateEngine;

use Magento\Framework\View\Element\BlockInterface;

class Php
{
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    public function __construct(\Magento\Framework\Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    public function beforeRender(
        \Magento\Framework\View\TemplateEngine\Php $subject,
        BlockInterface $block,
        $fileName,
        array $dictionary = []
    ) {
        if (!isset($dictionary['escaper'])) {
            $dictionary['escaper'] = $this->escaper;
        }

        return [$block, $fileName, $dictionary];
    }
}
