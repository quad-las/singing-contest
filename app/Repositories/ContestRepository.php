<?php

namespace App\Repositories;

use App\Models\Contest;

class ContestRepository
{
    public static function saveContest(array $winners): void
    {
        foreach ($winners as $winner) {
            $contest = new Contest();
            $contest->winner = array_keys($winner)[0];
            $contest->winning_score = array_values($winner)[0];
            $contest->save();
        }
    }

    public static function getLeaderBoard(int $limit): array
    {
        $leaders = Contest::orderBy('id', 'desc')
            ->select('winner', 'winning_score')
            ->limit($limit)
            ->get()
            ->toArray();

        $max = Contest::max('winning_score');


        $all_time_highs = Contest::select('winner', 'winning_score')
            ->where('winning_score', $max)
            ->get();

        return [
            'leaders' => $leaders,
            'all_time_highs' => $all_time_highs,
        ];
    }
}
