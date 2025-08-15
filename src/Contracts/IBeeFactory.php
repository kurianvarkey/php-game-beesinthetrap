<?php

/**
 * Interface for the bee factory and is used for the testing
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Contracts;

use KVP\Beesinthetrap\Enums\BeeRole;

interface IBeeFactory
{
    public function make(BeeRole $role): IBee;
}
