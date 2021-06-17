<?php

namespace App\Domain;

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

    public static function resetGenreForNewContest()
    {
        $genres = collect(self::GENRES);

        // cache $genres
    }

    public static function getGenreForCurrentRound(): string
    {
        // get random genre
        // remove from collection and update cache
        // return genre
    }
}