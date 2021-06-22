<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use App\Http\Controllers\ContestController;
use App\Domain\Repositories\ContestRepository;
use App\Domain\Services\Contestant;
use App\Domain\Services\Judge;
use App\Domain\Services\Genre;
use App\Domain\Services\Score;
use App\Domain\Services\Round;
use Mockery as Mockery;
use MockData as MD;

class ContestTest extends TestCase
{
    /** @var ContestController */
    private $contestController;
    
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

    /** @var Judge */
    private $judge;

    public function setUp(): void
    {
        parent::setUp();

        $this->genre = Mockery::mock(Genre::class);
        $this->judge = Mockery::mock(Judge::class);
        $this->score = Mockery::mock(Score::class);
        $this->round = Mockery::mock(Round::class);
        $this->contestant = Mockery::mock(Contestant::class);
        $this->contestRepo = Mockery::mock(ContestRepository::class);

        $this->contestController = new ContestController(
            $this->genre,
            $this->judge,
            $this->score,
            $this->round,
            $this->contestant,
            $this->contestRepo
        );
    }

    public function testContestStartsSuccessfully()
    {
        $this->round->shouldReceive('contestIsOngoing')
            ->once()
            ->andReturn(false);
        
        $this->genre->shouldReceive('setGenresForNewContest')
            ->once();

        $this->round->shouldReceive('setRounds')
            ->once()
            ->andReturn(MD::NUMBER_OF_ROUNDS);

        $this->contestant->shouldReceive('registerContestants')
            ->with()
            ->once()
            ->andReturn(
                collect(MD::CONTESTANTS)
            );
        
        $this->judge->shouldReceive('registerJudgesForContest')
            ->once()
            ->andReturn(
                collect(MD::JUDGES)
            );
        
        $start = $this->contestController->start();

        $this->assertInstanceOf(MD::VIEW, $start);
        $this->assertSame($start->getName(), 'index');
        $this->assertEquals($start->getData(), [
            'rounds' => MD::NUMBER_OF_ROUNDS,
            'contestants' => collect(MD::CONTESTANTS),
            'judges' => collect(MD::JUDGES),
        ]);
    }

    public function testThereCanOnlyBeOneContestAtATime()
    {
        $this->round->shouldReceive('contestIsOngoing')
            ->once()
            ->andReturn(true);

        $start = $this->contestController->start();

        // should redirect to '/board' to continue the ongoing contest
        $this->assertInstanceOf(MD::REDIRECT, $start);
        $this->assertSame($start->getTargetUrl(), URL('/play'));
    }

    public function testSuccessfulRoundPlay()
    {
        $this->genre->shouldReceive('getGenreForCurrentRound')
            ->once()
            ->andReturn(MD::GENRE);

        $this->score->shouldReceive('computeRoundScore')
            ->with(MD::GENRE)
            ->once()
            ->andReturn([MD::GENRE => MD::ROUND_SCORES]);

        $this->round->shouldReceive('moreRoundsLeft')
            ->once()
            ->andReturn(MD::ROUNDS_LEFT);

        $play = $this->contestController->play();

        $this->assertInstanceOf(MD::VIEW, $play);
        $this->assertSame($play->getName(), 'index');
        $this->assertEquals($play->getData(), [
            'rounds' => MD::ROUNDS_LEFT,
            'scores' => [MD::GENRE => MD::ROUND_SCORES],
        ]);
    }

    /**
     * Scenario 'no/out of genres'. Each round takes a genre of music from the list available.
     * This scenario can happen when the '/board' route is called (1)after the max number of rounds/genres has
     * been reached or (2)before starting a new contest. These two scenarios basically mean there isn't an
     * ongoing contest.
     * The user would be redirected to the home page.
     */
    public function testRedirectsToHomePageIfOutOfGenres(Type $var = null)
    {
        $this->expectException(Throwable::class);

        $this->genre->shouldReceive('getGenreForCurrentRound')->once();

        $start = $this->contestController->play();

        $this->assertInstanceOf(MD::REDIRECT, $start);
        $this->assertSame($start->getTargetUrl(), URL('/'));
    }

    public function testContestIsSavedAndCloseAfterAllRoundsHaveBeenPlayed()
    {
        $this->genre->shouldReceive('getGenreForCurrentRound')
            ->once()
            ->andReturn(MD::GENRE);

        $this->score->shouldReceive('computeRoundScore')
            ->with(MD::GENRE)
            ->once()
            ->andReturn([MD::GENRE => MD::ROUND_SCORES]);

        $this->round->shouldReceive('moreRoundsLeft')
            ->once()
            ->andReturn(false);

        $this->score->shouldReceive('getWinners')
            ->once()
            ->andReturn(MD::WINNER);

        $this->contestRepo->shouldReceive('saveContest')
            ->once()
            ->with(MD::WINNER);

        $play = $this->contestController->play();

        $this->assertInstanceOf(MD::VIEW, $play);
        $this->assertSame($play->getName(), 'index');
        $this->assertEquals($play->getData(), [
            'winners' => MD::WINNER
        ]);
    }

    public function testLeaderBoard()
    {
        $this->contestRepo->shouldReceive('getLeaderBoard')
            ->once()
            ->andReturn(MD::LEADER_BOARD);

        $board = $this->contestController->leaderBoard();

        $this->assertInstanceOf(MD::VIEW, $board);
        $this->assertSame($board->getName(), 'leader-board');
        $this->assertEquals($board->getData(), MD::LEADER_BOARD);
    }
}
