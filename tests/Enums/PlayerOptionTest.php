<?php

declare(strict_types=1);

namespace Tests\Enums;

use KVP\Beesinthetrap\Enums\PlayerOption;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PlayerOption::class)]
final class PlayerOptionTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals('hit', PlayerOption::Hit->value);
        $this->assertEquals('auto', PlayerOption::Auto->value);
        $this->assertEquals('quit', PlayerOption::Quit->value);
    }

    public function testDisplayHints(): void
    {
        $this->assertEquals('Hit', PlayerOption::Hit->displayHints());
        $this->assertEquals('Auto (perform on your behalf)', PlayerOption::Auto->displayHints());
        $this->assertEquals('Quit', PlayerOption::Quit->displayHints());
    }

    public function testEnumCases(): void
    {
        $cases = PlayerOption::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(PlayerOption::Hit, $cases);
        $this->assertContains(PlayerOption::Auto, $cases);
        $this->assertContains(PlayerOption::Quit, $cases);
    }
}
