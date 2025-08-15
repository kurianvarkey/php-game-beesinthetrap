<?php

/**
 * DroneBee provider
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Models\Bee\Providers;

use KVP\Beesinthetrap\Config\GameConfig;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Bee;

final class DroneBee extends Bee
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($this->getLifespan());
    }

    /**
     * Get Role
     */
    public function getRole(): BeeRole
    {
        return BeeRole::Drone;
    }

    /**
     * Get Available Count
     */
    public function getAvailableCount(): int
    {
        return GameConfig::DRONE_COUNT;
    }

    /**
     * Get Lifespan
     */
    public function getLifespan(): int
    {
        return GameConfig::DRONE_LIFESPAN;
    }

    /**
     * Get Sting Points
     */
    public function getStingPoints(): int
    {
        return GameConfig::DRONE_STING_POINTS;
    }

    /**
     * Get Damage Points
     */
    public function getDamagePoints(): int
    {
        return GameConfig::DRONE_DAMAGE_POINTS;
    }
}
