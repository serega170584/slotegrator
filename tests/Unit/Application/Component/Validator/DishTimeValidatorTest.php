<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishTimeValidator;
use Meals\Application\Component\Validator\Exception\DishDatetimeNotDayException;
use Meals\Application\Component\Validator\Exception\DishDatetimeNotIntervalException;
use Prophecy\PhpUnit\ProphecyTrait;

class DishTimeValidatorTest
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $currentDate = new \DateTimeImmutable('2023-06-12 10:00:00');
        $fromDate = new \DateTimeImmutable('2023-06-12 09:00:00');
        $toDate = new \DateTimeImmutable('2023-06-12 22:00:00');
        $dayOfWeek = '01';
        $validator = new DishTimeValidator();
        verify($validator->validate($currentDate, $fromDate, $toDate, $dayOfWeek))->null();
    }

    public function testDayFail()
    {
        $this->expectException(DishDatetimeNotDayException::class);

        $currentDate = new \DateTimeImmutable('2023-06-13 10:00:00');
        $fromDate = new \DateTimeImmutable('2023-06-12 09:00:00');
        $toDate = new \DateTimeImmutable('2023-06-12 22:00:00');
        $dayOfWeek = '01';
        $validator = new DishTimeValidator();
        verify($validator->validate($currentDate, $fromDate, $toDate, $dayOfWeek))->null();
    }

    public function testTimeFail()
    {
        $this->expectException(DishDatetimeNotIntervalException::class);

        $currentDate = new \DateTimeImmutable('2023-06-12 07:00:00');
        $fromDate = new \DateTimeImmutable('2023-06-12 09:00:00');
        $toDate = new \DateTimeImmutable('2023-06-12 22:00:00');
        $dayOfWeek = '01';
        $validator = new DishTimeValidator();
        verify($validator->validate($currentDate, $fromDate, $toDate, $dayOfWeek))->null();
    }
}