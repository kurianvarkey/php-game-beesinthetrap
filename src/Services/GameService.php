<?php

/**
 * Game Service class
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Services;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Contracts\IRandomizer;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Hive\Hive;
use KVP\Beesinthetrap\Models\Player\Player;

class GameService
{
    /**
     * Message Bag
     */
    private array $messageBag = [];

    /**
     * Check if the game is started
     */
    private bool $started = false;

    /**
     * Constructor
     */
    public function __construct(
        private Player $player,
        private Hive $hive,
        private ?IRandomizer $randomizer = null
    ) {
        $this->randomizer ??= new Randomizer;
    }

    /**
     * Start the game
     */
    public function start(): void
    {
        $this->started = true;
        $this->hive->populateBees();
    }

    /**
     * Stop the game
     */
    public function stop(): void
    {
        $this->started = false;
    }

    /**
     * Check if the game is started
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * Get Player Current HP
     */
    public function getPlayerCurrentHP(): int
    {
        return $this->player->getCurrentHP();
    }

    /**
     * Get Player Total Hits
     */
    public function getPlayerTotalHits(): int
    {
        return $this->player->getTotalBeeHits();
    }

    /**
     * Get Bee Remaining Count
     */
    public function getBeeRemainingCount(BeeRole $role): int
    {
        return $this->hive->getBeesCount($role);
    }

    /**
     * Perform Player Hit
     */
    public function performPlayerHit(): void
    {
        // chances for player to miss
        if ($this->randomizer->random(1, 100) <= GameConfig::PLAYER_MISS_CHANCE) {
            $this->addToMessageBag('You just missed the hive, better luck next time!');

            return;
        }

        $bee = $this->hive->getRandomBee();
        if (empty($bee)) {
            $this->addToMessageBag('There are no bees!', GameConfig::MESSAGE_TYPE_ERROR);

            return;
        }

        $this->player->incrementBeeHits();
        $this->hive->hit($bee);
        $this->addToMessageBag('Direct Hit! You took '.$bee->getDamagePoints().' hit points from a '.$bee->getRole()?->name.' bee');
        if ($bee->isDead()) {
            $this->hive->removeBee($bee);
            if ($bee->getRole() === BeeRole::Queen) {
                $this->hive->clearAllBees();
                $this->addToMessageBag('The queen has been killed! You win!');
            } else {
                $this->addToMessageBag('You killed a '.$bee->getRole()?->name.' bee!');
            }
        }

        $this->checkGameStatus();
    }

    /**
     * Perform Bee Hit
     */
    public function performBeeHit(): void
    {
        if (! $this->started) {
            return;
        }

        // chances for bee to miss
        if ($this->randomizer->random(1, 100) <= GameConfig::BEE_MISS_CHANCE) {
            $this->addToMessageBag('Buzz! That was close! The bees just missed you!');

            return;
        }

        $bee = $this->hive->getRandomBee();
        if (empty($bee)) {
            $this->addToMessageBag('There are no bees!', GameConfig::MESSAGE_TYPE_ERROR);

            return;
        }

        $this->player->setBeeStings($bee->getStingPoints());
        $this->addToMessageBag('Sting! You just got stung by a '.$bee->getRole()?->name.' bee', GameConfig::MESSAGE_TYPE_ERROR);

        $this->checkGameStatus();
    }

    /**
     * Perform Player Hit Auto
     */
    public function performPlayerHitAuto(): void
    {
        while ($this->started) {
            $this->performPlayerHit();
            $this->performBeeHit();
        }
    }

    /**
     * Get Messages
     */
    public function getMessages(): array
    {
        return $this->messageBag;
    }

    /**
     * Clear Messages
     */
    public function clearMessages(): void
    {
        $this->messageBag = [];
    }

    /**
     * Get Game Summary
     */
    public function getGameSummary(): string
    {
        $str = '';
        if (! $this->player->isPlayerAlive()) {
            $str = 'It took '.$this->player->getTotalBeeStings().' stings for the hive to kill you.';
        } elseif (! $this->hive->isBeesAvailable()) {
            $str = 'It took '.$this->player->getTotalBeeHits().' hits to destroy the hive.';
        }

        return $str;
    }

    /**
     * Check Game Status
     */
    private function checkGameStatus(): void
    {
        if (! $this->hive->isBeesAvailable()) {
            $this->addToMessageBag('You win! You killed all the bees!');
            $this->stop();
        } elseif (! $this->player->isPlayerAlive()) {
            $this->addToMessageBag('You lose! You have been killed by the bees!', GameConfig::MESSAGE_TYPE_ERROR);
            $this->stop();
        }
    }

    /**
     * Add to Message Bag
     */
    private function addToMessageBag(string $message, string $type = GameConfig::MESSAGE_TYPE_INFO): void
    {
        $this->messageBag[] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}
