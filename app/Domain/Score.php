<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;
use App\Domain\Contestant;
use App\Domain\Judge;

class Score
{
    public static function scoreTheRound(string $genre)
    {
        // round score range: 0.1 - 10.0
        $round_score = round((rand(1, 100) / 10), 1);

        // score each consultant
        $contestants_scores[$genre] = self::scoreEachContestant($genre, $round_score);

        // save scores for the round
        $key = 'round_' . $genre;
        Cache::put($key, $contestants_scores);
    }

    private static function scoreEachContestant(string $genre, int $round_score): array
    {
        $isRockGenre = ($genre === 'rock');
        $scores = [];

        // get contestants
        $contestants = Contestant::getContestants();

        foreach ($contestants as $contestant) {
            $rating = $round_score * $contestant['strength'][$genre];

            $scores[] = [
                $contestant['name'] => Judge::getTotalScoreFromJudges($rating, $isRockGenre)
            ];
        }

        return $scores;
    }
}