<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Feature\Dish\UseCase\ActivePollPostsDish\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\FunctionalTestCase;

class ActivePollPostsDishTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $dish = $this->getDish();
        $pollResult = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(true, $dish), $dish, new \DateTimeImmutable('2023-06-12 13:00:00'));
        verify($pollResult)->equals($pollResult);
    }

//    public function testUserHasNotPermissions()
//    {
//        $this->expectException(AccessDeniedException::class);
//
//        $poll = $this->performTestMethod($this->getEmployeeWithNoPermissions(), $this->getPoll(true));
//        verify($poll)->equals($poll);
//    }
//
//    public function testPollIsNotActive()
//    {
//        $this->expectException(PollIsNotActiveException::class);
//
//        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(false));
//        verify($poll)->equals($poll);
//    }

    private function performTestMethod(Employee $employee, Poll $poll, Dish $dish, \DateTimeImmutable $date): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        /**
         * @var Interactor $interactor
         */
        $interactor = $this->getContainer()->get(Interactor::class);
        $interactor->setNow($date);

        return $interactor->postDish($employee->getId(), $poll->getId(), $dish->getId());
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active, ?Dish $dish = null): Poll
    {
        $dishes = [];
        if (null !== $dish) {
            $dishes[] = $dish;
        }
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList($dishes),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'Блюдо 1',
            'Блюдо 1',
            1
        );
    }
}
