<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Cache;
use App\Domain\Repositories\ContestRepository;
use App\Models\Contest;
use Mockery as Mockery;
use MockData as MD;

class ContestRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @var ContestRepository */
    private $contestRepo;
    
    /** @var Contest */
    private $contest;

    public function setUp(): void
    {
        parent::setUp();

        $this->contest = Mockery::mock(Contest::class)->makePartial();
        $this->contestRepo = new ContestRepository($this->contest, MD::TABLE);
    }

    public function testContestIsSavedSuccessfully()
    {
        Cache::shouldReceive('flush')->once();

        $this->contestRepo->saveContest(MD::WINNER);

        $this->seeInDatabase(MD::TABLE, [
            'winner' => array_keys(MD::WINNER[0])[0],
            'winning_score' => array_values(MD::WINNER[0])[0],
        ]);
    }
}
