<?php

namespace Dotsquares\Base\Model\LicenceService\Schedule\Checker;

interface SenderCheckerInterface
{
    /**
     * @param string $flag
     * @return bool
     */
    public function isNeedToSend(string $flag): bool;
}
