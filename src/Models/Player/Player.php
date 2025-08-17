<?php

/**
 * Player
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Models\Player;

use InvalidArgumentException;

class Player
{
    /**
     * Current HP
     */
    public int $currentHP;

    /**
     * Total number of bee hits
     */
    public int $totalBeeHits = 0;

    /**
     * Total number of bee stings
     */
    public int $totalBeeStings = 0;

    /**
     * Constructor
     */
    public function __construct(
        private int $maxHP
    ) {
        if ($maxHP <= 0) {
            throw new InvalidArgumentException('Max HP must be positive');
        }

        $this->currentHP = $this->maxHP;
    }

    /**
     * Get Max HP
     */
    public function getMaxHP(): int
    {
        return $this->maxHP;
    }

    /**
     * Get Current HP
     */
    public function getCurrentHP(): int
    {
        return $this->currentHP < 0 ? 0 : $this->currentHP;
    }

    /**
     * Increment Bee Hits
     */
    public function incrementBeeHits(): void
    {
        $this->totalBeeHits++;
    }

    /**
     * Get Total Bee Hits
     */
    public function getTotalBeeHits(): int
    {
        return $this->totalBeeHits;
    }

    /**
     * Get Total Bee Stings
     */
    public function getTotalBeeStings(): int
    {
        return $this->totalBeeStings;
    }

    /**
     * Is Player Alive
     */
    public function isPlayerAlive(): bool
    {
        return $this->currentHP > 0;
    }

    /**
     * Hit player with bee stings. $beeStingDamage is the amount of damage the player takes from a bee sting
     */
    public function hit(int $stingPoints): void
    {
        if ($stingPoints < 0) {
            throw new InvalidArgumentException('Bee stings cannot be negative');
        }

        $this->totalBeeStings++;
        $this->currentHP -= $stingPoints;
    }
}
