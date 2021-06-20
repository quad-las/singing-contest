<?php

namespace App\Domain\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Faker\Factory as Faker;
use App\Domain\Services\Genre;

class Contestant
{
    public static function registerContestants(int $number_of_contestants = 10): collection
    {
        $max = $number_of_contestants;
        $contestants = new collection();

        for ($i=1; $i <= $max; $i++) {
            $contestant = Faker::create();

            $name = $contestant->name();

            $contestants[] = [
                'name' => $name,
                'strength' => self::getGenreStrength(),
            ];

            // also, cache individual contestant and initialize score to 0
            // this will hold contestant's overall score after each round
            Cache::put($name, 0);
        }

        Cache::put('contestants', $contestants);

        return $contestants;
    }

    public static function getContestants(): collection
    {
        return Cache::get('contestants');
    }

    public static function isContestantSick(int $chance = 5): bool
    {
        return rand(1, 100) <= $chance;
    }

    private static function getGenreStrength(): array
    {
        return Genre::generateGenreStrength();
    }
}
