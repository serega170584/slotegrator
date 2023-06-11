<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollResult;

interface PollProviderInterface
{
    public function getActivePolls(): PollList;

    public function getPoll(int $pollId): Poll;

    public function getPollResult(int $employeeId, int $pollId, int $dishId, int $price): PollResult;
}
