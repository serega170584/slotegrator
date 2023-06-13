<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollResult;

class FakePollProvider implements PollProviderInterface
{
    /** @var Poll */
    private $poll;

    /** @var PollList */
    private $polls;

    public function getActivePolls(): PollList
    {
        return $this->polls;
    }

    public function getPoll(int $pollId): Poll
    {
        return $this->poll;
    }

    /**
     * @param Poll $poll
     */
    public function setPoll(Poll $poll): void
    {
        $this->poll = $poll;
    }

    /**
     * @param PollList $polls
     */
    public function setPolls(PollList $polls): void
    {
        $this->polls = $polls;
    }

    public function getPollResult(Employee $employee, Poll $poll, Dish $dish, int $floor): PollResult
    {
        return new PollResult(1, $poll, $employee, $dish, $floor);
    }
}
