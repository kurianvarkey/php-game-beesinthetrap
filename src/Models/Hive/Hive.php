<?php

/**
 * Hive
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Models\Hive;

use KVP\Beesinthetrap\Contracts\IBee;
use KVP\Beesinthetrap\Contracts\IBeeFactory;
use KVP\Beesinthetrap\Contracts\IRandomizer;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\BeeFactory;
use KVP\Beesinthetrap\Services\Randomizer;

class Hive
{
    private array $bees = [];

    /**
     * Constructor
     */
    public function __construct(
        private ?IBeeFactory $beeFactory = null,
        private ?IRandomizer $randomizer = null
    ) {
        $this->beeFactory ??= new BeeFactory;
        $this->randomizer ??= new Randomizer;
    }

    /**
     * Init to populate hive
     */
    public function populateBees(): void
    {
        foreach (BeeRole::cases() as $role) {
            $bee = $this->beeFactory->make($role);
            for ($i = 0; $i < $bee->getAvailableCount(); $i++) {
                $this->bees[] = clone $bee;
            }
        }
    }

    /**
     * Get Bees Count
     */
    public function getBeesCount(BeeRole $role): int
    {
        return count(array_filter($this->bees, fn (IBee $bee) => $bee->getRole() === $role)) ?? 0;
    }

    /**
     * Get Bees
     */
    public function getBees(): array
    {
        return $this->bees;
    }

    /**
     * Get Random Bee
     */
    public function getRandomBee(): ?IBee
    {
        if (empty($this->bees)) {
            return null;
        }

        $randomKey = $this->randomizer->arrayRand($this->bees);

        return $this->bees[$randomKey] ?? null;
    }

    /**
     * Remove Bee
     */
    public function hit(IBee $bee): void
    {
        $bee->damageLifespan($bee->getDamagePoints());
    }

    /**
     * Remove Bee
     */
    public function removeBee(IBee $bee): void
    {
        $index = array_search($bee, $this->bees, true);
        if ($index !== false) {
            unset($this->bees[$index]);
            $this->bees = array_values($this->bees); // Reindex array
        }
    }

    /**
     * Is Bees Available
     */
    public function isBeesAvailable(): bool
    {
        return ! empty($this->bees);
    }

    /**
     * Clear bees from hive
     */
    public function clearAllBees(): void
    {
        $this->bees = [];
    }
}
