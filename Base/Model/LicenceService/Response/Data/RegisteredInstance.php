<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\LicenceService\Response\Data;

use Dotsquares\Base\Model\SimpleDataObject;

class RegisteredInstance extends SimpleDataObject
{
    public const SYSTEM_INSTANCE_KEY = 'system_instance_key';

    /**
     * @param string $systemInstanceKey
     * @return $this
     */
    public function setSystemInstanceKey(string $systemInstanceKey): self
    {
        return $this->setData(self::SYSTEM_INSTANCE_KEY, $systemInstanceKey);
    }

    /**
     * @return string
     */
    public function getSystemInstanceKey(): string
    {
        return $this->getData(self::SYSTEM_INSTANCE_KEY);
    }
}
