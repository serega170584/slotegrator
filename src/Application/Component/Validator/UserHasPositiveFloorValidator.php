<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NegativeEmployeeFloorException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;

class UserHasPositiveFloorValidator
{
    public function validate(Employee $employee, Dish $dish): void
    {
        $floor = $employee->getFloor() - $dish->getPrice();
        if ($floor < 0) {
            throw new NegativeEmployeeFloorException();
        }
    }

}