<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
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
    protected $contestRepo;

    /** @var Contestant */
    protected $contestant;

    /** @var Genre */
    protected $genre;

    /** @var Score */
    protected $score;

    /** @var Round */
    protected $rounds;

    /** @var  Judge*/
    protected $judge;

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

    /**
     * Here, the winner is determined & published.
     * Winner & winning score both save in the DB.
     */
    public function closeContest(): array
    {
        $winners = $this->score->getWinners();

        $this->saveContest($winners);

        Cache::flush();
        return $winners;
    }

    public function leaderBoard(int $limit = 5): View
    {
        $data = $this->contestRepo->getLeaderBoard($limit);

        return view('leader-board', $data);
    }

    private function scoreTheRound(string $genre): array
    {
        return $this->score->computeRoundScore($genre);
    }

    private function saveContest(array $winners): void
    {
        $this->contestRepo->saveContest($winners);
    }
}
