<?php

declare(strict_types=1);

namespace Tests\Console;

use KVP\Beesinthetrap\Console\Command;
use KVP\Beesinthetrap\Contracts\IConsoleUi;
use KVP\Beesinthetrap\Services\GameService;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Command::class)]
final class CommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testConstructorWithDependencies(): void
    {
        $gameService = Mockery::mock(GameService::class);
        $ui = Mockery::mock(IConsoleUi::class);

        $command = new Command($gameService, $ui);

        $this->assertInstanceOf(Command::class, $command);
    }
}
