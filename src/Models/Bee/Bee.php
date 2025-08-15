<?php

/**
 * Bee abstract class
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Models\Bee;

use InvalidArgumentException;
use KVP\Beesinthetrap\Contracts\IBee;

abstract class Bee implements IBee
{
    private int $lifespan;

    /**
     * Constructor
     */
    public function __construct(int $lifespan)
    {
        if ($lifespan <= 0) {
            throw new InvalidArgumentException('Lifespan must be positive');
        }

        $this->lifespan = $lifespan;
    }

    /**
     * Damage Lifespan
     */
    public function damageLifespan(int $damageCount): void
    {
        if ($damageCount < 0) {
            throw new InvalidArgumentException('Damage count cannot be negative');
        }

        $this->lifespan -= $damageCount;
    }

    /**
     * Get Remaining Lifespan
     */
    public function getRemainingLifespan(): int
    {
        return $this->lifespan;
    }

    /**
     * Is Dead
     */
    public function isDead(): bool
    {
        return $this->lifespan <= 0;
    }
}
