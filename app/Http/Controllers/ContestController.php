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

        $contestants = Contestant::registerContestants();
        $judges = Judge::registerJudgesForContest();

        return [
            'contestants' => $contestants,
            'judges' => $judges,
        ];
    }

    public function play(int $round)
    {
        $genre = Genre::getGenreForCurrentRound();

        $scores = $this->scoreTheRound($genre);
        
        if ($round === 6) {
            return $this->closeContest();
        }

        return $scores;
    }

    private function scoreTheRound(string $genre)
    {
        return Score::computeRoundScore($genre);
    }

    /**
     * Here, the winner is determined & published.
     * Winner & winning score both save in DB.
     */
    public function closeContest(): array
    {
        $winner = Score::getWinner();
        return $winner;
    }
}
