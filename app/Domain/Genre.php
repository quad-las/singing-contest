<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;

class Genre
{
    const GENRES = [
        'rock',
        'country',
        'pop',
        'disco',
        'jazz',
        'the blues',
    ];

    public static function generateGenreStrength(): array
    {
        $genres = self::GENRES;
        $genre_strength = [];

        foreach ($genres as $key) {
            $genre_strength[] = [
                $key => rand(1, 10)
            ];
        }

        return $genre_strength;
    }

    public static function resetGenresForNewContest()
    {
        $genres = collect(self::GENRES);

        // cache $genres
        Cache::put('genres', $genres);
    }

    public static function getGenreForCurrentRound(): string
    {
        // get random genre
        $genres = Cache::get('genres');
        $genre = $genres->random();

        // remove from collection and update cache
        unset($genres[$genre]);

        if (count($genres)) {
            Cache::put('genres', $genres);
        } else {
            Cache::forget('genres');
        }

        return $genre;
    }
}