<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NegativeEmployeeFloorException;
use Meals\Domain\Employee\Employee;

class UserHasPositiveFloorValidator
{
    public function validate(Employee $employee, int $price): void
    {
        $floor = $employee->getFloor() - $price;
        if ($floor < 0) {
            throw new NegativeEmployeeFloorException();
        }
    }

}