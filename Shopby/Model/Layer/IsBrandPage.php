<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Model\Layer;

use Dotsquares\Shopby\Model\Request;

class IsBrandPage
{
    public const DSBRAND_INDEX_INDEX = 'dsbrand_index_index';

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): bool
    {
        return $this->request->getFullActionName() === self::DSBRAND_INDEX_INDEX;
    }
}
