<?php

declare(strict_types=1);

namespace Tests\Enums;

use KVP\Beesinthetrap\Enums\BeeRole;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BeeRole::class)]
final class BeeRoleTest extends TestCase
{
    public function test_enum_values(): void
    {
        $this->assertEquals('queen', BeeRole::Queen->value);
        $this->assertEquals('worker', BeeRole::Worker->value);
        $this->assertEquals('drone', BeeRole::Drone->value);
    }

    public function test_enum_cases(): void
    {
        $cases = BeeRole::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(BeeRole::Queen, $cases);
        $this->assertContains(BeeRole::Worker, $cases);
        $this->assertContains(BeeRole::Drone, $cases);
    }
}
