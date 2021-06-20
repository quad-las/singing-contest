<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Domain\Repositories\ContestRepository;
use App\Domain\Services\Contestant;
use App\Domain\Services\Judge;
use App\Domain\Services\Genre;
use App\Domain\Services\Score;
use App\Domain\Services\Round;

class ContestController extends Controller
{
    /** @var ContestRepository */
    private $contestRepo;

    /** @var Contestant */
    private $contestant;

    /** @var Genre */
    private $genre;

    /** @var Score */
    private $score;

    /** @var Round */
    private $rounds;

    /** @var  Judge*/
    private $judge;

    public function __construct(
        ?Genre $genre,
        ?Judge $judge,
        ?Score $score,
        ?Round $round,
        ?Contestant $contestant,
        ?ContestRepository $contestRepo
    ) {
        $this->genre = $genre ?? new Genre;
        $this->judge = $judge ?? new Judge;
        $this->score = $score ?? new Score;
        $this->round = $round ?? new Round;
        $this->contestant = $contestant ?? new Contestant;
        $this->contestRepo = $contestRepo ?? new ContestRepository;
    }

    /**
     * @return RedirectResponse|View
     */
    public function start()
    {
        if ($this->round->contestIsOngoing()) {
            return redirect('/play');
        }
        
        $this->genre->setGenresForNewContest();

        $rounds = $this->round->setRounds();
        $contestants = $this->contestant->registerContestants();
        $judges = $this->judge->registerJudgesForContest();

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
            $genre = $this->genre->getGenreForCurrentRound();
        } catch (\Throwable $th) {
            return redirect('/');
        }
        
        $scores = $this->scoreTheRound($genre);
        
        $rounds = $this->round->moreRoundsLeft();

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

    public function leaderBoard(): View
    {
        $data = $this->contestRepo->getLeaderBoard();

        return view('leader-board', $data);
    }

    private function scoreTheRound(string $genre): array
    {
        return $this->score->computeRoundScore($genre);
    }

    /**
     * Here, the winner is determined & published.
     * Winner & winning score both save in the DB.
     */
    private function closeContest(): array
    {
        $winners = $this->score->getWinners();

        $this->contestRepo->saveContest($winners);

        return $winners;
    }
}
