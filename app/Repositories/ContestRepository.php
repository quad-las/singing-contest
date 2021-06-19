<?php

namespace App\Repositories;

use App\Models\Contest;

class ContestRepository
{
    public static function saveContest(array $winners)
    {
        foreach ($winners as $winner) {
            $contest = new Contest();
            $contest->winner = array_keys($winner)[0];
            $contest->winning_score = array_values($winner)[0];
            $contest->save();
        }
    }

    public static function getContests()
    {
        
    }
}