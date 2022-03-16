<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo;

use Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo;
use Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfoFactory;
use Magento\Framework\Api\DataObjectHelper;

class Converter
{
    /**
     * @var InstanceInfoFactory
     */
    private $instanceInfoFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    public function __construct(
        InstanceInfoFactory $instanceInfoFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->instanceInfoFactory = $instanceInfoFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    public function convertToObject(array $data): InstanceInfo
    {
        $addIfEmpty = [InstanceInfo::DOMAINS => [], InstanceInfo::MODULES => []];
        foreach ($addIfEmpty as $field => $value) {
            if (!isset($data[$field])) {
                $data[$field] = $value;
            }
        }

        $instanceInfo = $this->instanceInfoFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $instanceInfo,
            $data,
            InstanceInfo::class
        );

        return $instanceInfo;
    }
}
