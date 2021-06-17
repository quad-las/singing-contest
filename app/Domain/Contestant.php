<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;
use Faker\Factory as Faker;
use App\Domain\Genre;

class Contestant
{
    public static function registerContestants(int $number_of_contestants = 10)
    {
        $max = $number_of_contestants;
        $contestants = [];

        for ($i=1; $i <= $max; $i++) {
            $contestant = Faker::create();

            $contestants[] = [
                'name' => $contestant->name(),
                'strength' => self::getGenreStrength(),
            ];
        }

        // cache contestants
        Cache::put('contestants', $contestants);

        return $contestants;
    }

    public static function getContestants()
    {
        return Cache::get('contestants');
    }

    private static function getGenreStrength()
    {
        return Genre::generateGenreStrength();
    }

    // TODO: sick contestant
}