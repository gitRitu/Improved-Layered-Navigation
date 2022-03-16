<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Formatter;

interface FormatterInterface
{
    public function getContent(): string;

    public function getExtension(): string;
}
