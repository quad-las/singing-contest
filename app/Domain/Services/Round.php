<?php

namespace App\Domain\Services;

use Illuminate\Support\Facades\Cache;

class Round
{
    public static function setRounds(int $number_of_rounds = 6): int
    {
        Cache::put('rounds', $number_of_rounds - 1);
        return $number_of_rounds;
    }

    /**
     * @return int|bool
     */
    public static function moreRoundsLeft()
    {
        $rounds = Cache::get('rounds');
        if ($rounds <= 0) {
            return false;
        }

        Cache::decrement('rounds');
        return $rounds;
    }

    public static function contestIsOngoing(): bool
    {
        return Cache::get('rounds') ? true : false;
    }
}
