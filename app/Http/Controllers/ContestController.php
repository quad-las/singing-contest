<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Repositories\ContestRepository;
use App\Domain\Services\Contestant;
use App\Domain\Services\Judge;
use App\Domain\Services\Genre;
use App\Domain\Services\Score;
use App\Domain\Services\Round;

class ContestController extends Controller
{
    /**
     * @return RedirectResponse|View
     */
    public function start()
    {
        if (Round::contestIsOngoing()) {
            return redirect('/play');
        }
        
        Genre::setGenresForNewContest();

        $rounds = Round::setRounds();
        $contestants = Contestant::registerContestants();
        $judges = Judge::registerJudgesForContest();

        return view('index', [
            'rounds' => $rounds,
            'contestants' => $contestants,
            'judges' => $judges,
        ]);
    }

    /**
     * @return RedirectResponse|View
     */
    public function play()
    {
        try {
            $genre = Genre::getGenreForCurrentRound();
        } catch (\Throwable $th) {
            return redirect('/');
        }
        
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
     * Winner & winning score both save in the DB.
     */
    public function closeContest(): array
    {
        $winners = Score::getWinners();

        $this->saveContest($winners);

        Cache::flush();
        return $winners;
    }

    public function leaderBoard(int $limit = 5): View
    {
        $data = ContestRepository::getLeaderBoard($limit);

        return view('leader-board', $data);
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
