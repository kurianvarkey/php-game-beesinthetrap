<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Hive;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\Providers\DroneBee;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DroneBee::class)]
#[UsesClass(Bee::class)]
class DroneBeeTest extends TestCase
{
    public function test_worker_bee(): void
    {
        // create a drone Bee
        $bee = new DroneBee;

        // check instance
        $this->assertInstanceOf(Bee::class, $bee);
        $this->assertInstanceOf(DroneBee::class, $bee);

        // check attributes
        $availableCount = GameConfig::DRONE_COUNT;
        $lifespan = GameConfig::DRONE_LIFESPAN;
        $stingPoints = GameConfig::DRONE_STING_POINTS;
        $damagePoints = GameConfig::DRONE_DAMAGE_POINTS;

        $this->assertSame(BeeRole::Drone, $bee->getRole());
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
