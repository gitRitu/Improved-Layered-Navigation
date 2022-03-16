<?php

namespace Dotsquares\Base\Model\Import\Validation;

interface ValidatorPoolInterface
{
    /**
     * @return \Dotsquares\Base\Model\Import\Validation\ValidatorInterface[]
     */
    public function getValidators();

    /**
     * @param \Dotsquares\Base\Model\Import\Validation\ValidatorInterface
     *
     * @return void
     */
    public function addValidator($validator);
}
