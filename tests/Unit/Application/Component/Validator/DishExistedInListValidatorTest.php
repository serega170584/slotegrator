<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishExistedInListValidator;
use Meals\Application\Component\Validator\Exception\DishIsNotExistedInListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DishExistedInListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $dish = $this->prophesize(Dish::class);
        $dishes = $this->prophesize(DishList::class);
        $dishes->getDishes()->willReturn([$dish->reveal()]);
        $dishes->hasDish($dish->reveal())->willReturn(true);

        $validator = new DishExistedInListValidator();
        verify($validator->validate($dish->reveal(), $dishes->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(DishIsNotExistedInListException::class);

        $dish = $this->prophesize(Dish::class);
        $dishes = $this->prophesize(DishList::class);
        $dishes->getDishes()->willReturn([$dish->reveal()]);
        $dishes->hasDish($dish->reveal())->willReturn(false);

        $validator = new DishExistedInListValidator();
        verify($validator->validate($dish->reveal(), $dishes->reveal()))->null();
    }
}