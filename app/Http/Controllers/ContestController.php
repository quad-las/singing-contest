<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Repositories\ContestRepository;
use App\Domain\Contestant;
use App\Domain\Judge;
use App\Domain\Genre;
use App\Domain\Score;

class ContestController extends Controller
{
    public function start(): View
    {
        Cache::flush();
        Genre::resetGenresForNewContest();

        $contestants = Contestant::registerContestants();
        $judges = Judge::registerJudgesForContest();

        return view('index', [
            'contestants' => $contestants,
            'judges' => $judges,
        ]);
    }

    public function play(int $round): View
    {
        $genre = Genre::getGenreForCurrentRound();

        $scores = $this->scoreTheRound($genre);
        
        if ($round === 6) {
            return $this->closeContest();
        }

        return view('index', ['scores' => $scores]);
    }

    /**
     * Here, the winner is determined & published.
     * Winner & winning score both save in DB.
     */
    public function closeContest(): array
    {
        $winners = Score::getWinners();

        $this->saveContest($winners);

        return $winners;
    }

    public function leaderBoard(int $limit = 5): array
    {
        return ContestRepository::getLeaderBoard($limit);
    }

    private function scoreTheRound(string $genre): array
    {
        return Score::computeRoundScore($genre);
    }

    private function saveContest(array $winners)
    {
        ContestRepository::saveContest($winners);
    }
}
