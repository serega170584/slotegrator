<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DishIsNotExistedInListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class DishExistedInListValidator
{
    public function validate(Dish $dish, DishList $dishList): void
    {
        if (!$dishList->hasDish($dish)) {
            throw new DishIsNotExistedInListException();
        }
    }

}