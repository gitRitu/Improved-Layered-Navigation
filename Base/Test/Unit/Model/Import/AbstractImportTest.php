<?php

namespace Dotsquares\Base\Test\Unit\Model\Import;

use Dotsquares\Base\Model\Import\AbstractImport;
use Dotsquares\Base\Model\Import\Validation\ValidatorPoolInterface;
use Dotsquares\Base\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class AbstractImportTest
 *
 * @see AbstractImport
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class AbstractImportTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @covers AbstractImport::processValidation
     */
    public function testProcessValidation()
    {
        $model = $this->createPartialMock(AbstractImport::class, []);
        $validatorPool = $this->createMock(ValidatorPoolInterface::class, []);
        $validator1 = $this->createMock(\Dotsquares\Base\Model\Import\Validation\ValidatorInterface::class, []);
        $validator2 = $this->createMock(\Dotsquares\Base\Model\Import\Validation\ValidatorInterface::class, []);
        $exception = $this->createMock(\Dotsquares\Base\Exceptions\StopValidation::class, []);

        $validatorPool->expects($this->any())->method('getValidators')
            ->willReturnOnConsecutiveCalls([], [$validator1, $validator2]);
        $validator1->expects($this->any())->method('validateRow')->willReturn(['test']);
        $exception->expects($this->any())->method('getValidateResult')->willReturn([1 => 'error']);
        $validator2->expects($this->any())->method('validateRow')
            ->willThrowException($exception);

        $this->setProperty($model, 'validatorPool', $validatorPool, AbstractImport::class);
        $this->assertFalse($this->invokeMethod($model, 'processValidation', [[]]));

        $this->assertEquals(['test', 'error'], $this->invokeMethod($model, 'processValidation', [[]]));
    }
}
