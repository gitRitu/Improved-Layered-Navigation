<?php

namespace Dotsquares\GroupedOptions\Model\GroupAttr;

interface DataFactoryProviderInterface
{
    public function create(array $data = []): DataProvider;
}
