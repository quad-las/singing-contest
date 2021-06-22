<?php

namespace App\Domain\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Faker\Factory as Faker;
use App\Domain\Services\Genre;

class Contestant
{
    /** @var Genre */
    private $genre;

    public function __construct(?Genre $genre)
    {
        $this->genre = $genre ?? new Genre;
    }

    public function registerContestants(int $number_of_contestants = 10): collection
    {
        $max = $number_of_contestants;
        $contestants = new collection();

        for ($i=1; $i <= $max; $i++) {
            $contestant = Faker::create();

            $name = $contestant->name();

            $contestants[] = [
                'name' => $name,
                'strength' => $this->getGenreStrength(),
            ];

            // also, cache individual contestant and initialize score to 0
            // this will hold contestant's overall score after each round
            Cache::put($name, 0);
        }

        Cache::put('contestants', $contestants);

        return $contestants;
    }

    public function getContestants(): collection
    {
        return Cache::get('contestants');
    }

    public function isContestantSick(int $chance = 5): bool
    {
        return rand(1, 100) <= $chance;
    }

    private function getGenreStrength(): array
    {
        return $this->genre->generateGenreStrength();
    }
}
