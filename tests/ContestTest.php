<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Domain\Services\Genre;

class ContestTest extends TestCase
{
    use DatabaseMigrations;

    public function testAllRoutesResponseStatus()
    {
        $home = $this->call('GET', '/');
        $this->assertEquals(200, $home->status());

        $start = $this->call('GET', '/start');
        $this->assertEquals(200, $start->status());

        $play = $this->call('GET', '/play');
        $this->assertEquals(200, $play->status());

        $leader_board = $this->call('GET', '/leader-board');
        $this->assertEquals(200, $leader_board->status());
    }
}
