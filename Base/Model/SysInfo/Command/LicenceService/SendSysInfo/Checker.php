<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo;

class Checker
{
    public function isChangedCacheValue(?string $cacheValue, string $newValue): bool
    {
        return !($cacheValue && hash_equals($cacheValue, $newValue));
    }
}
