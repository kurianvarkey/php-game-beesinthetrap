<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Player;

use InvalidArgumentException;
use KVP\Beesinthetrap\Models\Player\Player;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Player::class)]
class PlayerTest extends TestCase
{
    public function test_player(): void
    {
        $player = new Player(100);
        $this->assertInstanceOf(Player::class, $player);

        $this->assertEquals(100, $player->getMaxHP());
        $this->assertEquals(100, $player->getCurrentHP());
        $this->assertEquals(0, $player->getTotalBeeHits());

        $player->incrementBeeHits();
        $this->assertEquals(1, $player->getTotalBeeHits());

        $this->assertTrue($player->isPlayerAlive());

        $player->hit(10);

        $this->assertEquals(1, $player->getTotalBeeStings());
        $this->assertEquals(90, $player->getCurrentHP());

        $player->hit(20);

        $this->assertEquals(2, $player->getTotalBeeStings());
        $this->assertEquals(70, $player->getCurrentHP());

        $player->hit(100);
        $this->assertFalse($player->isPlayerAlive());
    }

    public function test_player_must_be_positive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Max HP must be positive');

        $player = new Player(-1);
        unset($player);
    }

    public function test_player_sting_pointmust_be_positive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bee stings cannot be negative');

        $player = new Player(100);
        $player->hit(-1);
    }
}
