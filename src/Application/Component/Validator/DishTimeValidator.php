<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DishDatetimeNotDayException;
use Meals\Application\Component\Validator\Exception\DishDatetimeNotIntervalException;

class DishTimeValidator
{
    /**
     * @throws \Exception
     */
    public function validate(\DateTimeImmutable $currentDate, \DateTimeImmutable $fromDate, \DateTimeImmutable $toDate, string $dayOfWeek): void
    {
        if ($currentDate->format('w') !== $dayOfWeek) {
            throw new DishDatetimeNotDayException();
        }

        if ($currentDate < $fromDate || $toDate < $currentDate) {
            throw new DishDatetimeNotIntervalException();
        }
    }
}