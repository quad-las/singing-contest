<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Domain\Contestant;
use App\Domain\Judge;
use App\Domain\Genre;

class ContestController extends Controller
{
    public function create()
    {
        // TODO: contest


        // TODO: register consultants 
        $contestants = Contestant::registerContestants();

        // TODO: get judges 
        $judges = Judge::getJudgesForContest();

        return [
            'contestants' => $contestants,
            'judges' => $judges,
        ];
    }

    public function play(int $round)
    {
        // get next genre for this round
        $genre = Genre::getGenreForCurrentRound();
    }

    public function saveRoundScore(int $round, string $genre)
    {
        // cache scores

        // get consultants from cache and get judge scores
        $contestants = [];

        $scores = Judge::scoreTheRound($genre);
    }
}
