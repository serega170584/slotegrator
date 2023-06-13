<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NegativeEmployeeFloorException;
use Meals\Application\Component\Validator\UserHasPositiveFloorValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class UserHasPositiveFloorValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $employee = $this->prophesize(Employee::class);
        $employee->getFloor()->willReturn(4);
        $dish = $this->prophesize(Dish::class);
        $dish->getPrice()->willReturn(1);

        $validator = new UserHasPositiveFloorValidator();
        verify($validator->validate($employee->reveal(), $dish->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(NegativeEmployeeFloorException::class);

        $employee = $this->prophesize(Employee::class);
        $employee->getFloor()->willReturn(4);
        $dish = $this->prophesize(Dish::class);
        $dish->getPrice()->willReturn(5);

        $validator = new UserHasPositiveFloorValidator();
        verify($validator->validate($employee->reveal(), $dish->reveal()))->null();
    }
}