<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class Judge
{
    const MEAN_JUDGE = 'mean';
    const RANDOM_JUDGE = 'random';
    const FRIENDLY_JUDGE = 'friendly';
    const ROCK_JUDGE = 'rock';
    const HONEST_JUDGE = 'honest';

    public static function registerJudgesForContest(): collection
    {
        $judges = collect([
            self::MEAN_JUDGE,
            self::RANDOM_JUDGE,
            self::FRIENDLY_JUDGE,
            self::ROCK_JUDGE,
            self::HONEST_JUDGE,
        ])->random(3);
        
        // cache $judges
        Cache::put('judges', $judges);

        return $judges;
    }

    public static function getTotalScoreFromJudges(int $rating, bool $isRockGenre, bool $contestant_is_sick): int
    {
        $judges = Cache::get('judges');

        $each_score = [];

        foreach ($judges as $judge) {
            switch ($judge) {
                case self::MEAN_JUDGE:
                    $each_score[] = self::scoreByMeanJudge($rating);
                    break;
                
                case self::RANDOM_JUDGE:
                    $each_score[] = self::scoreByRandomJudge($rating);
                    break;
                    
                case self::FRIENDLY_JUDGE:
                    $each_score[] = self::scoreByFriendlyJudge($rating, $contestant_is_sick);
                    break;

                case self::ROCK_JUDGE:
                    $each_score[] = self::scoreByRockJudge($rating, $isRockGenre);
                    break;

                case self::HONEST_JUDGE:
                    $each_score[] = self::scoreByHonestJudge($rating);
                    break;
                
                default:
                    break;
            }
        }

        return array_sum($each_score);
    }

    private static function scoreByMeanJudge(int $rating): int
    {
        return $rating > 90 ? 10 : 2;
    }

    private static function scoreByRandomJudge(int $rating): int
    {
        return rand(0, 10);
    }

    private static function scoreByFriendlyJudge(int $rating, bool $contestant_is_sick): int
    {
        $bonus_point = 1;

        $score = $rating > 3 ? 8 : 7;

        return $contestant_is_sick ? ($score + $bonus_point) : $score;
    }

    private static function scoreByRockJudge(int $rating, bool $isRockGenre): int
    {
        if (!$isRockGenre) {
            return rand(0, 10);
        }

        $score = 0;

        switch ($rating) {
            case $rating < 50:
                $score = 5;
                break;

            case $rating < 75:
                $score = 8;
                break;
            
            default:
                $score = 10;
                break;
        }

        return $score;
    }

    private static function scoreByHonestJudge(int $rating): int
    {
        return ceil($rating / 10);
    }
}
