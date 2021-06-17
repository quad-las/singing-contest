<?php

namespace App\Domain;

use Illuminate\Support\Collection;
use App\Domain\Contestant;

class Judge
{
    const MEAN_JUDGE = 'mean';
    const RANDOM_JUDGE = 'random';
    const FRIENDLY_JUDGE = 'friendly';
    const ROCK_JUDGE = 'rock';
    const HONEST_JUDGE = 'honest';

    public static function getJudgesForContest(): collection
    {
        $judges = collect([
            self::MEAN_JUDGE,
            self::RANDOM_JUDGE,
            self::FRIENDLY_JUDGE,
            self::ROCK_JUDGE,
            self::HONEST_JUDGE,
        ])->random(3);
        
        // cache

        return $judges;
    }

    public static function scoreTheRound(string $genre)
    {
        //  round score range: 0.1 - 10.0
        $round_score = round((rand(1, 100) / 10), 1);

        // score each consultant
        $contestants_scores[$genre] = self::scoreEachContestant($genre, $round_score);
    }

    private static function scoreEachContestant(string $genre, int $round_score)
    {
        $isRockGenre = ($genre === 'rock') ? true : false;
        $scores = [];

        // get contestants from cache
        $contestants = Contestant::getContestants();

        foreach ($contestants as $contestant) {
            $rating = $round_score * $contestant['strength'][$genre];

            $scores[] = [
                $contestant['name'] => self::getTotalScoreFromJudges($rating, $isRockGenre)
            ];
        }
    }

    private static function getTotalScoreFromJudges(int $rating, bool $isRockGenre): int
    {
        // get contest judges
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
                    $each_score[] = self::scoreByFriendlyJudge($rating);
                    break;

                case self::ROCK_JUDGE:
                    $each_score[] = self::scoreByRockJudge($rating, $isRockGenre);
                    break;

                case self::HONEST_JUDGE:
                    $each_score[] = self::scoreByHonestJudge($rating);
                    break;
                
                default:
                    # code...
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

    private static function scoreByFriendlyJudge(int $rating): int
    {
        $bonus_point = 1;
        $contestantIsSick = false;

        $score = $rating > 3 ? 8 : 7;

        return $contestantIsSick ? ($score + $bonus_point) : $score;
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
