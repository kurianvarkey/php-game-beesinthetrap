<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use KVP\Beesinthetrap\Contracts\IRandomizer;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\BeeFactory;
use KVP\Beesinthetrap\Models\Bee\Providers\DroneBee;
use KVP\Beesinthetrap\Models\Bee\Providers\QueenBee;
use KVP\Beesinthetrap\Models\Bee\Providers\WorkerBee;
use KVP\Beesinthetrap\Models\Hive\Hive;
use KVP\Beesinthetrap\Models\Player\Player;
use KVP\Beesinthetrap\Services\GameService;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GameService::class)]
#[UsesClass(Player::class)]
#[UsesClass(Hive::class)]
#[UsesClass(Bee::class)]
#[UsesClass(BeeFactory::class)]
#[UsesClass(QueenBee::class)]
#[UsesClass(WorkerBee::class)]
#[UsesClass(DroneBee::class)]
class GameServiceTest extends TestCase
{
    private $playerMock;

    private $hiveMock;

    private $randomizerMock;

    private GameService $gameService;

    protected function setup(): void
    {
        $this->playerMock = Mockery::mock(Player::class);
        $this->hiveMock = Mockery::mock(Hive::class);
        $this->randomizerMock = Mockery::mock(IRandomizer::class);

        $this->gameService = new GameService(
            $this->playerMock,
            $this->hiveMock,
            $this->randomizerMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_game_service_state()
    {
        $this->assertInstanceOf(GameService::class, $this->gameService);
    }

    public function test_start_game()
    {
        $this->hiveMock->shouldReceive('populateBees')
            ->once()
            ->withNoArgs();

        $this->hiveMock->shouldReceive('getBeesCount')
            ->once()
            ->with(BeeRole::Queen)
            ->andReturn(1);

        $this->gameService->start();
        $this->assertTrue($this->gameService->isStarted());
        $this->assertGreaterThan(0, $this->gameService->getBeeRemainingCount(BeeRole::Queen));
    }

    public function test_stop_game()
    {
        $this->hiveMock->shouldReceive('populateBees')
            ->once()
            ->withNoArgs();

        $this->gameService->start();
        $this->gameService->stop();
        $this->assertFalse($this->gameService->isStarted());
    }

    public function test_get_player_current_hp()
    {
        $this->hiveMock->shouldReceive('populateBees')
            ->once()
            ->withNoArgs();

        $this->playerMock->shouldReceive('getCurrentHP')
            ->once()
            ->andReturn(100);

        $this->gameService->start();
        $this->assertEquals(100, $this->gameService->getPlayerCurrentHP());
    }

    public function test_get_player_total_hits()
    {
        $this->hiveMock->shouldReceive('populateBees')
            ->once()
            ->withNoArgs();

        $this->playerMock->shouldReceive('getTotalBeeHits')
            ->once()
            ->andReturn(50);

        $this->gameService->start();
        $this->assertEquals(50, $this->gameService->getPlayerTotalHits());
    }

    public function test_get_bee_remaining_count()
    {
        $this->hiveMock->shouldReceive('populateBees')
            ->once()
            ->withNoArgs();

        $this->hiveMock->shouldReceive('getBeesCount')
            ->once()
            ->andReturn(1);

        $this->gameService->start();
        $this->assertEquals(1, $this->gameService->getBeeRemainingCount(BeeRole::Queen));
    }

    public function test_message_bag()
    {
        $this->randomizerMock->shouldReceive('random')
            ->once()
            ->andReturn(1);

        $this->gameService->performPlayerHit();
        $this->assertEquals(1, count($this->gameService->getMessages()));

        $this->gameService->clearMessages();
        $this->assertEquals(0, count($this->gameService->getMessages()));
    }

    public function test_get_game_summary_is_player_alive()
    {
        $this->playerMock->shouldReceive('isPlayerAlive')
            ->once()
            ->andReturn(false);

        $this->playerMock->shouldReceive('getTotalBeeStings')
            ->once()
            ->andReturn(10);

        $this->assertEquals('It took 10 stings for the hive to kill you.', $this->gameService->getGameSummary());
    }

    public function test_get_game_summary_get_total_bee_hits()
    {
        $this->playerMock->shouldReceive('isPlayerAlive')
            ->once()
            ->andReturn(true);

        $this->playerMock->shouldReceive('getTotalBeeHits')
            ->once()
            ->andReturn(50);

        $this->hiveMock->shouldReceive('isBeesAvailable')
            ->once()
            ->andReturn(false);

        $this->assertEquals('It took 50 hits to destroy the hive.', $this->gameService->getGameSummary());
    }

    public function test_perform_player_hit_miss()
    {
        // test for a miss
        $this->randomizerMock->shouldReceive('random')
            ->once()
            ->andReturn(1);

        $this->gameService->performPlayerHit();
        $this->assertEquals('You just missed the hive, better luck next time!', $this->gameService->getMessages()[0]['message']);
    }

    public function test_perform_player_hit_no_bees()
    {
        // test for no bees
        $this->randomizerMock->shouldReceive('random')
            ->once()
            ->andReturn(80);

        $this->hiveMock->shouldReceive('getRandomBee')
            ->once()
            ->andReturn(null);

        $this->gameService->performPlayerHit();
        $this->assertEquals('There are no bees!', $this->gameService->getMessages()[0]['message']);
    }

    // test for a random bee
}
