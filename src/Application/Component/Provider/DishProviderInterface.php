<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Dish\Dish;

interface DishProviderInterface
{
    public function getDish(int $dishId): Dish;
}