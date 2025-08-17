<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Hive;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\Providers\WorkerBee;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WorkerBee::class)]
#[UsesClass(Bee::class)]
class WorkerBeeTest extends TestCase
{
    public function test_worker_bee(): void
    {
        // create a worker Bee
        $bee = new WorkerBee;

        // check instance
        $this->assertInstanceOf(Bee::class, $bee);
        $this->assertInstanceOf(WorkerBee::class, $bee);

        // check attributes
        $availableCount = GameConfig::WORKER_COUNT;
        $lifespan = GameConfig::WORKER_LIFESPAN;
        $stingPoints = GameConfig::WORKER_STING_POINTS;
        $damagePoints = GameConfig::WORKER_DAMAGE_POINTS;

        $this->assertSame(BeeRole::Worker, $bee->getRole());
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
