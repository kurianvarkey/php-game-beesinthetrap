<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Hive;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\Providers\QueenBee;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(QueenBee::class)]
#[UsesClass(Bee::class)]
class QueenBeeTest extends TestCase
{
    public function test_queen_bee(): void
    {
        // create a Queen Bee
        $bee = new QueenBee;

        // check instance
        $this->assertInstanceOf(Bee::class, $bee);
        $this->assertInstanceOf(QueenBee::class, $bee);

        // check attributes
        $availableCount = GameConfig::QUEEN_COUNT;
        $lifespan = GameConfig::QUEEN_LIFESPAN;
        $stingPoints = GameConfig::QUEEN_STING_POINTS;
        $damagePoints = GameConfig::QUEEN_DAMAGE_POINTS;

        $this->assertSame(BeeRole::Queen, $bee->getRole());
        $this->assertSame($availableCount, $bee->getAvailableCount());
        $this->assertSame($lifespan, $bee->getLifespan());
        $this->assertSame($stingPoints, $bee->getStingPoints());
        $this->assertSame($damagePoints, $bee->getDamagePoints());

        // checking lifespan
        $this->assertSame($lifespan, $bee->getRemainingLifespan());

        $remainingLifespan = $lifespan - $damagePoints;
        $bee->damageLifespan($damagePoints);
        $this->assertSame($remainingLifespan, $bee->getRemainingLifespan());
        $this->assertFalse($bee->isDead());

        $bee->damageLifespan($remainingLifespan);
        $this->assertSame(0, $bee->getRemainingLifespan());
        $this->assertTrue($bee->isDead());
    }
}
