<?php

declare(strict_types=1);

namespace Dotsquares\Base\Test\Unit\Model\SysInfo\Command\LicenceService;

use Dotsquares\Base\Model\LicenceService\Api\RequestManager;
use Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo;
use Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo;
use Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\ChangedData\Persistor as ChangedDataPersistor;
use Dotsquares\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\Converter;
use Dotsquares\Base\Model\SysInfo\Data\RegisteredInstance;
use Dotsquares\Base\Model\SysInfo\Data\RegisteredInstance\Instance;
use Dotsquares\Base\Model\SysInfo\RegisteredInstanceRepository;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SendSysInfoTest extends TestCase
{
    /**
     * @var SendSysInfo
     */
    private $model;

    /**
     * @var RegisteredInstanceRepository|MockObject
     */
    private $registeredInstanceRepositoryMock;

    /**
     * @var ChangedDataPersistor|MockObject
     */
    private $changedDataPersistorMock;

    /**
     * @var Converter|MockObject
     */
    private $converterMock;

    /**
     * @var RequestManager|MockObject
     */
    private $requestManagerMock;

    protected function setUp(): void
    {
        $this->registeredInstanceRepositoryMock = $this->createMock(RegisteredInstanceRepository::class);
        $this->changedDataPersistorMock = $this->createMock(ChangedDataPersistor::class);
        $this->converterMock = $this->createMock(Converter::class);
        $this->requestManagerMock = $this->createMock(RequestManager::class);

        $this->model = new SendSysInfo(
            $this->registeredInstanceRepositoryMock,
            $this->changedDataPersistorMock,
            $this->converterMock,
            $this->requestManagerMock
        );
    }

    /**
     * @param array $changedData
     * @dataProvider executeDataProvider
     * @return void
     */
    public function testExecute(array $changedData): void
    {
        list($instanceInfoMock, $systemInstanceKey) = $this->initExecute($changedData);

        if ($changedData) {
            $this->requestManagerMock
                ->expects($this->once())
                ->method('updateInstanceInfo')
                ->with($instanceInfoMock);
            $this->changedDataPersistorMock
                ->expects($this->once())
                ->method('save')
                ->with($changedData);
        } else {
            $this->requestManagerMock
                ->expects($this->once())
                ->method('ping')
                ->with($systemInstanceKey);
        }

        $this->model->execute();
    }

    public function testExecuteOnException(): void
    {
        $changedData = [[], []];
        list($instanceInfoMock) = $this->initExecute($changedData);

        $this->requestManagerMock
            ->expects($this->once())
            ->method('updateInstanceInfo')
            ->with($instanceInfoMock)
            ->willThrowException(new LocalizedException(__('Invalid Request.')));

        $this->expectException(LocalizedException::class);
        $this->model->execute();
    }

    public function testExecuteWithEmptySystemInstanceKey(): void
    {
        $this->initInstanceMock(null);

        $this->model->execute();
    }

    private function initExecute(array $changedData): array
    {
        $systemInstanceKey = 'systemInstanceKey';
        $instanceInfoMock = $this->createMock(InstanceInfo::class);
        $instanceMock = $this->createMock(Instance::class);
        $instanceMock
            ->expects($this->atLeastOnce())
            ->method('getSystemInstanceKey')
            ->willReturn($systemInstanceKey);

        $this->initInstanceMock($instanceMock);

        $this->changedDataPersistorMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($changedData);
        if ($changedData) {
            $this->converterMock
                ->expects($this->once())
                ->method('convertToObject')
                ->with($changedData)
                ->willReturn($instanceInfoMock);
            $instanceInfoMock
                ->expects($this->once())
                ->method('setSystemInstanceKey')
                ->with($systemInstanceKey);
        }

        return [$instanceInfoMock, $systemInstanceKey];
    }

    private function initInstanceMock(?Instance $instanceMock): void
    {
        $registeredInstanceMock = $this->createMock(RegisteredInstance::class);

        $this->registeredInstanceRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($registeredInstanceMock);
        $registeredInstanceMock
            ->expects($this->atLeastOnce())
            ->method('getCurrentInstance')
            ->willReturn($instanceMock);
    }

    public function executeDataProvider(): array
    {
        return [
            [[[], []]],
            [[]]
        ];
    }
}
