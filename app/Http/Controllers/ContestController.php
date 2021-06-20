<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Repositories\ContestRepository;
use App\Domain\Services\{
    Contestant,
    Judge,
    Genre,
    Score,
    Round
};

class ContestController extends Controller
{
    public function start(): View
    {
        Cache::flush();
        Genre::resetGenresForNewContest();

        $rounds = Round::resetRounds();
        $contestants = Contestant::registerContestants();
        $judges = Judge::registerJudgesForContest();

        return view('index', [
            'rounds' => $rounds,
            'contestants' => $contestants,
            'judges' => $judges,
        ]);
    }

    public function play(): View
    {
        $genre = Genre::getGenreForCurrentRound();
        
        $scores = $this->scoreTheRound($genre);
        
        $rounds = Round::moreRoundsLeft();        

        if (!$rounds) {
            $data = ['winners' => $this->closeContest()];
        } else {
            $data = [
                'rounds' => $rounds,
                'scores' => $scores,
            ];
        }

        return view('index', $data);
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

    private function saveContest(array $winners): void
    {
        ContestRepository::saveContest($winners);
    }
}
