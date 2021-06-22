<?php

use Illuminate\Support\Facades\Cache;
use App\Domain\Services\Contestant;
use App\Domain\Services\Genre;
use Mockery as Mockery;
use MockData as MD;

class ContestantTest extends TestCase
{
    /** @var Contestant */
    private $contestant;

    /** @var Genre */
    private $genre;


    public function setUp(): void
    {
        parent::setUp();

        $this->genre = Mockery::mock(Genre::class);
        $this->contestant = new Contestant($this->genre);
    }

    public function testRegisterConsultants()
    {
        $this->genre->shouldReceive('generateGenreStrength')
            ->twice()
            ->andReturn(MD::GENRE_STRENGTH);

        Cache::shouldReceive('put')->twice()->andReturn(true);
        Cache::shouldReceive('put')->once()->andReturn(true);

        $contestants = $this->contestant->registerContestants(MD::NUMBER_OF_CONTESTANTS);
        
        $this->assertEquals(MD::NUMBER_OF_CONTESTANTS, count($contestants));
    }

    public function testGetContestants()
    {
        Cache::shouldReceive('get')
            ->once()
            ->andReturn(
                collect(MD::CONTESTANTS)
            );

        $contestants = $this->contestant->getContestants();

        $this->assertEquals(MD::CONTESTANTS, $contestants->toArray());
    }
}
