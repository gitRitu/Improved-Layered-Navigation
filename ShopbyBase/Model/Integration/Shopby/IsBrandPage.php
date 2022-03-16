<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Model\Integration\Shopby;

class IsBrandPage
{
    /**
     * @var array
     */
    private $data;

    public function __construct(
        array $data = []
    ) {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        return isset($this->data['object']) ? $this->data['object']->execute() : false;
    }
}
