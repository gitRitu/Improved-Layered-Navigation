<?php

namespace Dotsquares\Base\Exceptions;

class StopValidation extends \Exception
{
    /**
     * @var array|bool
     */
    protected $validateResult;

    /**
     * @param array|bool $validateResult
     */
    public function __construct($validateResult)
    {
        $this->validateResult = $validateResult;
    }

    /**
     * @return array|bool
     */
    public function getValidateResult()
    {
        return $this->validateResult;
    }
}
