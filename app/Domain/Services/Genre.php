<?php

namespace App\Domain\Services;

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
            $genre_strength[$key] = rand(1, 10);
        }

        return $genre_strength;
    }

    public static function setGenresForNewContest(): void
    {
        $genres = collect(self::GENRES);

        Cache::put('genres', $genres);
    }

    /**
     * @throws \Throwable
     */
    public static function getGenreForCurrentRound(): string
    {
        $genres = Cache::get('genres');
        $genre = $genres->random();

        // remove from collection and update cache to prevent
        // having same genre in more than one round
        $genres->forget(array_search($genre, $genres->toArray()));

        if (count($genres)) {
            Cache::put('genres', $genres->values());
        } else {
            Cache::forget('genres');
        }

        return $genre;
    }
}