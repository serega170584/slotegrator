<?php

namespace Meals\Application\Feature\Dish\UseCase\ActivePollPostsDish;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Validator\DishExistedInListValidator;
use Meals\Application\Component\Validator\DishTimeValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Application\Component\Validator\UserHasPositiveFloorValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    private const FROM_TIME = '08:00:00';
    private const TO_TIME = '22:00:00';
    private const DAY_OF_WEEK = '1';

    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /**
     * @var UserHasAccessToViewPollsValidator
     */
    private $userHasAccessToPollsValidator;

    /**
     * @var PollIsActiveValidator
     */
    private $pollIsActiveValidator;

    /**
     * @var UserHasPositiveFloorValidator
     */
    private $userHasPositiveFloorValidator;

    /**
     * @var DishProviderInterface
     */
    private $dishProvider;

    /**
     * @var DishExistedInListValidator
     */
    private $dishExistedInListValidator;

    /**
     * @var DishTimeValidator
     */
    private $dishTimeValidator;

    /**
     * @var \DateTimeImmutable|null
     */
    private $now = null;

    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        UserHasPositiveFloorValidator $userHasPositiveFloorValidator,
        DishProviderInterface $dishProvider,
        DishExistedInListValidator $dishExistedInListValidator,
        DishTimeValidator $dishTimeValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
        $this->userHasPositiveFloorValidator = $userHasPositiveFloorValidator;
        $this->dishProvider = $dishProvider;
        $this->dishExistedInListValidator = $dishExistedInListValidator;
        $this->dishTimeValidator = $dishTimeValidator;
    }

    /**
     * @throws \Exception
     */
    public function postDish(int $employeeId, int $pollId, int $dishId) : PollResult
    {
        $currentDate = (null === $this->now) ? new \DateTimeImmutable() : $this->now;
        $dateStr = $currentDate->format('Y-m-d');
        $fromDate = new \DateTimeImmutable($dateStr . ' ' . self::FROM_TIME);
        $toDate = new \DateTimeImmutable($dateStr . ' ' .  self::TO_TIME);
        $dayOfWeek = self::DAY_OF_WEEK;
        $this->dishTimeValidator->validate($currentDate, $fromDate, $toDate, $dayOfWeek);

        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);

        $this->userHasAccessToPollsValidator->validate($employee->getUser());

        $this->pollIsActiveValidator->validate($poll);

        $dish = $this->dishProvider->getDish($dishId);
        $dishes = $poll->getMenu()->getDishes();
        $this->dishExistedInListValidator->validate($dish, $dishes);

        $this->userHasPositiveFloorValidator->validate($employee, $dish);

        return $this->pollProvider->getPollResult($employee, $poll, $dish, $employee->getFloor() - $dish->getPrice());
    }

    public function setNow(\DateTimeImmutable $now): void
    {
        $this->now = $now;
    }
}