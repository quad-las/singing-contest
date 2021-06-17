<?php

namespace App\Domain;

use Faker\Factory as Faker;
use App\Domain\Genre;

class Contestants
{   
    private $number_of_contestants;

    public function __construct($number_of_contestants = 10)
    {
        $this->number_of_contestants = $number_of_contestants;
    }

    public function generateContestants()
    {
        $max = $this->number_of_contestants;
        $contestants = [];

        for ($i=1; $i <= $max; $i++) {
            $contestant = Faker::create();

            $contestants[] = [
                'name' => $contestant->name(),
                'strength' => $this->getGenreStrength(),
            ];
        }

        return $contestants;
    }

    private function getGenreStrength()
    {
        return Genre::generateGenreStrength();
    }

}