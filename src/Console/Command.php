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
        private ?ConsoleUi $ui = null
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
        $this->ui->clear();

        $this->playerName = $this->ui->text(
            label: 'What is your name?',
            placeholder: 'Your name',
            required: true,
        );

        $name = ucfirst($this->playerName);
        $maimumHP = GameConfig::PLAYER_MAX_HP;

        $queenCount = GameConfig::QUEEN_COUNT;
        $queenLifespan = GameConfig::QUEEN_LIFESPAN;
        $queenStingPoint = GameConfig::QUEEN_STING_POINTS;
        $queenDamage = GameConfig::QUEEN_DAMAGE_POINTS;

        $workerCount = GameConfig::WORKER_COUNT;
        $workerLifespan = GameConfig::WORKER_LIFESPAN;
        $workerStingPoint = GameConfig::WORKER_STING_POINTS;
        $workerDamage = GameConfig::WORKER_DAMAGE_POINTS;

        $droneCount = GameConfig::DRONE_COUNT;
        $droneLifespan = GameConfig::DRONE_LIFESPAN;
        $droneStingPoint = GameConfig::DRONE_STING_POINTS;
        $droneDamage = GameConfig::DRONE_DAMAGE_POINTS;

        $message = <<<EOT
        ** Hi $name, welcome to Bees in the Trap Game **
        -------------------------------------------------------------------------
        
        Each player's mission is to destroy the hive by killing all the bees in the hive. After every player's hit, it is bees turn to hit the player.
        Hit can also be missed.

        Options:
        1. Enter 'Hit' to hit the bee
        2. Enter 'Auto' to hit the bee automatically
        3. Enter 'Quit' to quit the game

        Each player has a maximum of  $maimumHP HP.

        There are 3 types of bees in the hive:
        1. Queen Bee. Total: $queenCount Lifespan: $queenLifespan, each hit will reduce the lifespan by $queenDamage. Each sting reduces the player's HP by $queenStingPoint.
        2. Worker Bee. Total: $workerCount Lifespan: $workerLifespan, each hit will reduce the lifespan by $workerDamage. Each sting reduces the player's HP by $workerStingPoint.
        3. Drone Bee. Total: $droneCount Lifespan: $droneLifespan, each hit will reduce the lifespan by $droneDamage. Each sting reduces the player's HP by $droneStingPoint.

        When a bee dies, it will be removed from the hive. And when queen bee dies, all bees in the hive will be removed and the game will be over.
        Game will be over when all bees are dead or player is dead.

        Good Luck!
        EOT;
        
        $this->ui->note($message);
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
            $summary = $this->gameService->getGameSummary();
            $this->ui->note('** Game Over **'.PHP_EOL.($summary != '' ? $summary : 'User opted to quit the game.'));
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
