<?php

declare(strict_types=1);

namespace Dotsquares\Base\Cron;

use Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo;
use Dotsquares\Base\Model\LicenceService\Schedule\Checker\Daily;

class DailySendSystemInfo
{
    public const FLAG_KEY = 'dotsquares_base_daily_send_system_info';

    /**
     * @var SendSysInfo
     */
    private $sysInfo;

    /**
     * @var Daily
     */
    private $dailyChecker;

    public function __construct(
        SendSysInfo $sysInfo,
        Daily $dailyChecker
    ) {
        $this->sysInfo = $sysInfo;
        $this->dailyChecker = $dailyChecker;
    }

    public function execute()
    {
        if ($this->dailyChecker->isNeedToSend(self::FLAG_KEY)) {
            $this->sysInfo->execute();
        }
    }
}
