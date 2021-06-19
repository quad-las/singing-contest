<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;
use App\Domain\Contestant;
use App\Domain\Judge;

class Score
{
    public static function computeRoundScore(string $genre): array
    {
        // round score range: 0.1 - 10.0
        $round_score = round((rand(1, 100) / 10), 1);

        $contestants_scores[$genre] = self::computeAndSaveContestantScore($genre, $round_score);

        return $contestants_scores;
    }

    public static function getWinners(): array
    {
        $contestants = Contestant::getContestants()->pluck('name');
        $overall_contestant_scores = [];

        foreach ($contestants as $contestant) {
            $overall_contestant_scores[] = [$contestant => Cache::get($contestant)];
        }

        $highest_score = max($overall_contestant_scores);

        return array_filter(
            $overall_contestant_scores,
            fn($score) => $highest_score == $score
        );
    }

    private static function computeAndSaveContestantScore(string $genre, int $round_score): array
    {
        $isRockGenre = ($genre === 'rock');
        $scores = [];

        $contestants = Contestant::getContestants();

        foreach ($contestants as $contestant) {
            $contestant_is_sick = Contestant::isContestantSick();

            $rating = $round_score * $contestant['strength'][$genre];
            
            if ($contestant_is_sick) {
                $rating = $rating / 2;
            }

            $contestant_score = Judge::getTotalScoreFromJudges(
                $rating,
                $isRockGenre,
                $contestant_is_sick    
            );

            $name = $contestant['name'];

            $scores[] = [
                'name' => $name,
                'score' => $contestant_score,
                'sick' => $contestant_is_sick,
            ];

            // update contestant's overall score in cache
            Cache::increment($name, $contestant_score);
        }

        return $scores;
    }
}