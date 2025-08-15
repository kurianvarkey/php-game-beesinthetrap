<?php

/**
 * WorkerBee provider
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

final class WorkerBee extends Bee
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
        return BeeRole::Worker;
    }

    /**
     * Get Available Count
     */
    public function getAvailableCount(): int
    {
        return GameConfig::WORKER_COUNT;
    }

    /**
     * Get Lifespan
     */
    public function getLifespan(): int
    {
        return GameConfig::WORKER_LIFESPAN;
    }

    /**
     * Get Sting Points
     */
    public function getStingPoints(): int
    {
        return GameConfig::WORKER_STING_POINTS;
    }

    /**
     * Get Damage Points
     */
    public function getDamagePoints(): int
    {
        return GameConfig::WORKER_DAMAGE_POINTS;
    }
}
