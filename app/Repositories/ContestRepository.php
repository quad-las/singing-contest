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
        $leaders = Contest::orderBy('winning_score', 'desc')
            ->select('winner', 'winning_score')
            ->limit($limit)
            ->get()
            ->toArray();

        $max = $leaders[0]['winning_score'];

        $all_time_highs = array_filter(
            $leaders,
            fn($score) => $max == $score['winning_score']
        );

        return [
            'leaders' => $leaders,
            'all_time_highs' => $all_time_highs,
        ];
    }
}