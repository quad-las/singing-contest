<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Domain\Contestant;
use App\Domain\Judge;
use App\Domain\Genre;
use App\Domain\Score;

class ContestController extends Controller
{
    public function create()
    {
        Cache::flush();
        Genre::resetGenresForNewContest();
        // TODO: contest

        $contestants = Contestant::registerContestants();
        $judges = Judge::registerJudgesForContest();

        return [
            'contestants' => $contestants,
            'judges' => $judges,
        ];
    }

    public function play(int $round): bool
    {
        // get next genre for this round
        $genre = Genre::getGenreForCurrentRound();

        $this->saveRoundScore($genre);

        if ($round === 6) {
            // close content
        }

        return true;
    }

    public function saveRoundScore(string $genre)
    {
        $scores = Score::scoreTheRound($genre);
    }
}
