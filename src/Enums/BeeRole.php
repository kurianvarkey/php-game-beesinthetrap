<?php

/**
 * Enum for the bee roles
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Enums;

enum BeeRole: string
{
    case Queen = 'queen';
    case Worker = 'worker';
    case Drone = 'drone';
}
