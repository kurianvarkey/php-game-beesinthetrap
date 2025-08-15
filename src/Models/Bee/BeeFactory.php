<?php

/**
 * BeeFactory
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Models\Bee;

use InvalidArgumentException;
use KVP\Beesinthetrap\Contracts\IBee;
use KVP\Beesinthetrap\Contracts\IBeeFactory;
use KVP\Beesinthetrap\Enums\BeeRole;
use KVP\Beesinthetrap\Models\Bee\Providers\DroneBee;
use KVP\Beesinthetrap\Models\Bee\Providers\QueenBee;
use KVP\Beesinthetrap\Models\Bee\Providers\WorkerBee;

final class BeeFactory implements IBeeFactory
{
    /**
     * Make Bee based on role
     */
    public function make(BeeRole $role): IBee
    {
        return match ($role) {
            BeeRole::Queen => new QueenBee,
            BeeRole::Worker => new WorkerBee,
            BeeRole::Drone => new DroneBee,
            default => throw new InvalidArgumentException('Invalid Bee Role: '.$role->name),
        };
    }
}
