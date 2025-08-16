<?php

/**
 * Console Command to run the game
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Console;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Contracts\IConsoleUi;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Enums\PlayerOption;
use KVP\Beesinthetrap\Models\Hive\Hive;
use KVP\Beesinthetrap\Models\Player\Player;
use KVP\Beesinthetrap\Services\ConsoleUi;
use KVP\Beesinthetrap\Services\GameService;

final class Command
{
    /**
     * Player name
     */
    private string $playerName;

    /**
     * Options for the user from UserInput enum
     */
    private array $options = [];

    /**
     * User Opted Quit or not
     */
    private bool $userOptedQuit = false;

    /**
     * Constructor
     */
    public function __construct(
        private ?GameService $gameService = null,
        private ?IConsoleUi $ui = null
    ) {
        $this->gameService ??= new GameService(
            new Player(GameConfig::PLAYER_MAX_HP),
            new Hive
        );

        $this->ui ??= new ConsoleUi;
    }

    /**
     * Run is the main entry point
     */
    public function run(): void
    {
        $this->options = $this->getPlayetInputOptions();

        $this->init();

        $this->gameService->start();

        while ($this->gameService->isStarted()) {
            $this->displayStats();
            $userInput = $this->getUserInput();

            match ($userInput) {
                PlayerOption::Hit->value => $this->gameService->performPlayerHit(),
                PlayerOption::Auto->value => $this->gameService->performPlayerHitAuto(),
                PlayerOption::Quit->value => $this->gameService->stop(),
            };

            if ($userInput === PlayerOption::Quit->value) {
                $this->userOptedQuit = true;
            }

            $this->gameService->performBeeHit();
        }

        $this->displayStats(gameOver: true);
    }

    /**
     * Get Playet Input Options
     */
    private function getPlayetInputOptions(): array
    {
        return array_reduce(PlayerOption::cases(), function (array $options, PlayerOption $userInput) {
            $options[$userInput->value] = $userInput->displayHints();

            return $options;
        }, []);
    }

    /**
     * Init
     */
    private function init(): void
    {
        $this->playerName = $this->ui->text(
            label: 'What is your name?',
            placeholder: 'Your name',
            // default: 'Player 1',
            required: true,
        );

        $this->ui->note('** Hi '.ucfirst($this->playerName).', welcome to Bees in the Trap Game **');
    }

    /**
     * Display Stats
     */
    private function displayStats(bool $gameOver = false): void
    {
        foreach ($this->gameService->getMessages() as $message) {
            $this->ui->note($message['message'], $message['type']);
        }
        $this->gameService->clearMessages();

        if (! $this->userOptedQuit) {
            $this->displayGameStats();
        }

        if ($gameOver) {
            $this->ui->note('** Game Over **'.PHP_EOL.$this->gameService->getGameSummary());
        }
    }

    /**
     * Display Game Stats in a table
     */
    private function displayGameStats(): void
    {
        $this->ui->table(
            headers: ['Player Name', 'Hits', 'Remaining HP', 'Queen Bee Count', 'Worker Bee Count', 'Drone Bee Count'],
            rows: [[
                $this->playerName,
                $this->gameService->getPlayerTotalHits(),
                $this->gameService->getPlayerCurrentHP(),
                $this->gameService->getBeeRemainingCount(BeeRole::Queen),
                $this->gameService->getBeeRemainingCount(BeeRole::Worker),
                $this->gameService->getBeeRemainingCount(BeeRole::Drone),
            ]]
        );
    }

    /**
     * Get User Input
     */
    private function getUserInput(): string
    {
        return $this->ui->select(
            label: 'Please choose your action',
            options: $this->options,
        );
    }
}
