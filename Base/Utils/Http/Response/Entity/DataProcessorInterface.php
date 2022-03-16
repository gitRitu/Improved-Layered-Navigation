<?php

namespace Dotsquares\Base\Utils\Http\Response\Entity;

interface DataProcessorInterface
{
    public function process(array $data): array;
}
