<?php

namespace Dotsquares\Base\Model\Import\Validation;

class ValidatorPool implements ValidatorPoolInterface
{
    /**
     * @var \Dotsquares\Base\Model\Import\Validation\ValidatorInterface[]
     */
    private $validators;

    public function __construct(
        $validators
    ) {
        $this->validators = [];
        foreach ($validators as $validator) {
            if (!($validator instanceof ValidatorInterface)) {
                throw new \Dotsquares\Base\Exceptions\WrongValidatorInterface();
            }

            $this->validators[] = $validator;
        }
    }

    /**
     * @inheritdoc
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @inheritdoc
     */
    public function addValidator($validator)
    {
        if (!($validator instanceof ValidatorInterface)) {
            throw new \Dotsquares\Base\Exceptions\WrongValidatorInterface();
        }

        $this->validators[] = $validator;
    }
}
