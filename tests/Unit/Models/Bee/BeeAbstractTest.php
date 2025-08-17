<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Bee;

use InvalidArgumentException;
use KVP\Beesinthetrap\Contracts\IBee;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;
use KVP\Beesinthetrap\Models\Bee\Providers\DroneBee;
use KVP\Beesinthetrap\Models\Bee\Providers\QueenBee;
use KVP\Beesinthetrap\Models\Bee\Providers\WorkerBee;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Bee::class)]
#[UsesClass(QueenBee::class)]
#[UsesClass(WorkerBee::class)]
#[UsesClass(DroneBee::class)]
class BeeAbstractTest extends TestCase
{
    private Bee $bee;

    private int $lifespan = 100;

    private int $damagePoints = 5;

    private function getBeeClass(int $lifespan): Bee
    {
        return new class($lifespan) extends Bee implements IBee
        {
            public function getRole(): BeeRole
            {
                return BeeRole::Queen;
            }

            public function getAvailableCount(): int
            {
                return 1;
            }

            public function getLifespan(): int
            {
                return 100;
            }

            public function getStingPoints(): int
            {
                return 10;
            }

            public function getDamagePoints(): int
            {
                return 5;
            }
        };
    }

    protected function setUp(): void
    {
        $this->bee = $this->getBeeClass($this->lifespan);
    }

    public function test_bee_abstract_class_check_stats(): void
    {
        $this->assertInstanceOf(Bee::class, $this->bee); // check bee instance
        $this->assertInstanceOf(IBee::class, $this->bee); // check interface

        $this->assertSame(BeeRole::Queen, $this->bee->getRole());
        $this->assertSame(1, $this->bee->getAvailableCount());
        $this->assertSame($this->lifespan, $this->bee->getLifespan());
        $this->assertSame(10, $this->bee->getStingPoints());
        $this->assertSame($this->damagePoints, $this->bee->getDamagePoints());
    }

    public function test_bee_abstract_class_damage_lifespan(): void
    {
        // checking lifespan
        $remainingLifespan = $this->lifespan - $this->damagePoints;
        $this->bee->damageLifespan($this->damagePoints);
        $this->assertSame($remainingLifespan, $this->bee->getRemainingLifespan());

        $this->bee->damageLifespan($remainingLifespan);
        $this->assertSame(0, $this->bee->getRemainingLifespan());
    }

    public function test_bee_abstract_class_get_remaining_lifespan(): void
    {
        // checking lifespan
        $this->assertSame($this->lifespan, $this->bee->getRemainingLifespan());

        $remainingLifespan = $this->lifespan - $this->damagePoints;
        $this->bee->damageLifespan($this->damagePoints);
        $this->assertSame($remainingLifespan, $this->bee->getRemainingLifespan());

        $this->bee->damageLifespan($remainingLifespan);
        $this->assertSame(0, $this->bee->getRemainingLifespan());
    }

    public function test_bee_abstract_class_is_dead(): void
    {
        $this->assertFalse($this->bee->isDead());

        // checking lifespan
        $this->assertFalse($this->bee->isDead());

        $remainingLifespan = $this->lifespan - $this->damagePoints;
        $this->bee->damageLifespan($this->damagePoints);
        $this->assertFalse($this->bee->isDead());

        $this->bee->damageLifespan($remainingLifespan);
        $this->assertTrue($this->bee->isDead());
    }

    public function test_bee_with_invalid_lifespan(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Lifespan must be positive');

        $bee = $this->getBeeClass(-1);
        unset($bee);
    }

    public function test_bee_damage_with_invalid_damage_count(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Damage count cannot be negative');

        $bee = $this->getBeeClass(100);
        $bee->damageLifespan(-1);
    }
}
