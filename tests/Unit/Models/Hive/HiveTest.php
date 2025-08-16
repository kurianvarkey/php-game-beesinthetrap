<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Hive;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\BeeFactory;
use KVP\Beesinthetrap\Models\Bee\Providers\DroneBee;
use KVP\Beesinthetrap\Models\Bee\Providers\QueenBee;
use KVP\Beesinthetrap\Models\Bee\Providers\WorkerBee;
use KVP\Beesinthetrap\Models\Hive\Hive;
use KVP\Beesinthetrap\Services\Randomizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Hive::class)]
#[UsesClass(Bee::class)]
#[UsesClass(BeeFactory::class)]
#[UsesClass(QueenBee::class)]
#[UsesClass(WorkerBee::class)]
#[UsesClass(DroneBee::class)]
#[UsesClass(Randomizer::class)]
class HiveTest extends TestCase
{
    public function test_hive(): void
    {
        $hive = new Hive;
        // check instance
        $this->assertInstanceOf(Hive::class, $hive);

        // check initial state
        $this->assertEmpty($hive->getBees());

        // populate hive and check bees count based on config
        $count = GameConfig::QUEEN_COUNT + GameConfig::WORKER_COUNT + GameConfig::DRONE_COUNT;
        $hive->populateBees();
        $this->assertNotEmpty($hive->getBees());
        $this->assertCount($count, $hive->getBees());

        $this->assertSame(GameConfig::QUEEN_COUNT, $hive->getBeesCount(BeeRole::Queen));
        $this->assertSame(GameConfig::WORKER_COUNT, $hive->getBeesCount(BeeRole::Worker));
        $this->assertSame(GameConfig::DRONE_COUNT, $hive->getBeesCount(BeeRole::Drone));

        // check random bee and instance of bee
        $randomBee = $hive->getRandomBee();
        $this->assertInstanceOf(Bee::class, $randomBee);

        // hit random bee
        $hive->hit($randomBee);
        $this->assertSame($randomBee->getLifespan() - $randomBee->getDamagePoints(), $randomBee->getRemainingLifespan());
        $this->assertFalse($randomBee->isDead());

        // trying to hit based on lifespan
        $numHits = $randomBee->getLifespan() / $randomBee->getDamagePoints();
        for ($i = 1; $i < $numHits; $i++) { // we will start at 1 because we already hit once
            $hive->hit($randomBee);
        }
        $this->assertSame(0, $randomBee->getRemainingLifespan());
        $this->assertTrue($randomBee->isDead());

        // remove random bee
        $hive->removeBee($randomBee);
        $this->assertCount($count - 1, $hive->getBees());

        // check if bees are available
        $this->assertTrue($hive->isBeesAvailable());

        // clear all bees and check
        $hive->clearAllBees();
        $this->assertEmpty($hive->getBees());
        $this->assertFalse($hive->isBeesAvailable());

        // now hive is empty and try to get random bee
        $randomBee = $hive->getRandomBee();
        $this->assertNull($randomBee);
    }
}
