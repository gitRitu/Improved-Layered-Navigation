<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\LicenceService\Schedule;

use Dotsquares\Base\Model\FlagRepository;
use Dotsquares\Base\Model\LicenceService\Schedule\Data\ScheduleConfig;
use Dotsquares\Base\Model\LicenceService\Schedule\Data\ScheduleConfigFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Serialize\SerializerInterface;

class ScheduleConfigRepository
{
    /**
     * @var FlagRepository
     */
    private $flagRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ScheduleConfigFactory
     */
    private $scheduleConfigFactory;

    public function __construct(
        FlagRepository $flagRepository,
        SerializerInterface $serializer,
        DataObjectHelper $dataObjectHelper,
        ScheduleConfigFactory $scheduleConfigFactory
    ) {
        $this->flagRepository = $flagRepository;
        $this->serializer = $serializer;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->scheduleConfigFactory = $scheduleConfigFactory;
    }

    public function get(string $flag): ScheduleConfig
    {
        $scheduleConfigInstance = $this->scheduleConfigFactory->create();
        $scheduleConfigSerialized = $this->flagRepository->get($flag);
        $scheduleConfigArray = $this->serializer->unserialize($scheduleConfigSerialized);
        if (is_array($scheduleConfigArray)) {
            $this->dataObjectHelper->populateWithArray(
                $scheduleConfigInstance,
                $scheduleConfigArray,
                ScheduleConfig::class
            );
        }

        return $scheduleConfigInstance;
    }

    public function save(string $flag, ScheduleConfig $scheduleConfig): bool
    {
        $scheduleConfigArray = $scheduleConfig->toArray();
        $scheduleConfigSerialized = $this->serializer->serialize($scheduleConfigArray);
        $this->flagRepository->save($flag, $scheduleConfigSerialized);

        return true;
    }
}
