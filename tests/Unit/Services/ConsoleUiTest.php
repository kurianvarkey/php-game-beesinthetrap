<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use KVP\Beesinthetrap\Contracts\IConsoleUi;
use KVP\Beesinthetrap\Services\ConsoleUi;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConsoleUi::class)]
class ConsoleUiTest extends TestCase
{
    private $consoleUiProviderMock;

    protected function setup(): void
    {
        $this->consoleUiProviderMock = Mockery::mock(IConsoleUi::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_text()
    {
        $this->consoleUiProviderMock->shouldReceive('text')
            ->once()
            ->with('Enter your name', '', false, '', '')
            ->andReturn('John Doe');

        $ui = new ConsoleUi($this->consoleUiProviderMock);

        $result = $ui->text('Enter your name');
        $this->assertEquals('John Doe', $result);
    }

    public function test_select()
    {
        $this->consoleUiProviderMock->shouldReceive('select')
            ->once()
            ->with('Enter your age', [20, 30, 50], '')
            ->andReturn(30);

        $ui = new ConsoleUi($this->consoleUiProviderMock);

        $result = $ui->select('Enter your age', [20, 30, 50]);
        $this->assertEquals(30, $result);
    }

    public function test_note()
    {
        $this->consoleUiProviderMock->shouldReceive('note')
            ->once()
            ->with('You are checking the note', 'info');

        $ui = new ConsoleUi($this->consoleUiProviderMock);

        $ui->note('You are checking the note', 'info');
        $this->assertTrue(true);

        // this is a void method, but called for code coverage
    }

    public function test_table()
    {
        $this->consoleUiProviderMock->shouldReceive('table')
            ->once()
            ->with(['Name', 'Role'], [['John', 'Admin'], ['Jane', 'User']]);

        $ui = new ConsoleUi($this->consoleUiProviderMock);

        $ui->table(['Name', 'Role'], [['John', 'Admin'], ['Jane', 'User']]);
        $this->assertTrue(true);

        // this is a void method, but called for code coverage
    }
}
