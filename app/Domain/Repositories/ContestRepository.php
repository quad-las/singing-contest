<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Facades\Cache;
use App\Models\Contest;

class ContestRepository
{
    /** @var Contest */
    private $contest;

    public function __construct(?Contest $contest, string $table = null)
    {
        $this->contest = $contest ?? new Contest();
        if ($table) {
            $this->contest = $this->contest->setTable($table);
        }
    }

    public function saveContest(array $winners): void
    {
        foreach ($winners as $winner) {
            $this->contest->winner = array_keys($winner)[0];
            $this->contest->winning_score = array_values($winner)[0];
            $this->contest->save();
        }

        Cache::flush();
    }

    public function getLeaderBoard(int $limit = 5): array
    {
        $leaders = $this->contest->orderBy('id', 'desc')
            ->select('winner', 'winning_score')
            ->limit($limit)
            ->get()
            ->toArray();

        $max = $this->contest->max('winning_score');

        $all_time_highs = $this->contest->select('winner', 'winning_score')
            ->where('winning_score', $max)
            ->get();

        return [
            'leaders' => $leaders,
            'all_time_highs' => $all_time_highs,
        ];
    }
}
