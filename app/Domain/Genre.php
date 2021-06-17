<?php

namespace App\Domain;

class Genre
{
    const genres = [
        'rock',
        'country',
        'pop',
        'disco',
        'jazz',
        'the blues',
    ];

    public static function generateGenreStrength(): array
    {
        $genres = self::genres;
        $genre_strength = [];

        foreach ($genres as $key) {
            $genre_strength[] = [
                $key => rand(1,10)
            ];
        }

        return $genre_strength;
    }
}