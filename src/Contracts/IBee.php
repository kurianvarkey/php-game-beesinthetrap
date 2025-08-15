<?php

/**
 * Interface for the bee and is used for the testing
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

namespace KVP\Beesinthetrap\Contracts;

use KVP\Beesinthetrap\Enums\BeeRole;

interface IBee
{
    public function getRole(): BeeRole;

    public function getAvailableCount(): int;

    public function getLifespan(): int;

    public function getStingPoints(): int;

    public function getDamagePoints(): int;

    public function damageLifespan(int $damageCount): void;

    public function getRemainingLifespan(): int;

    public function isDead(): bool;
}
