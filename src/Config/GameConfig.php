<?php

/**
 * Confirguration for the game. Adjust the values here
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Config;

final class GameConfig
{
    public const PLAYER_MAX_HP = 100;

    public const PLAYER_MISS_CHANCE = 10;

    public const BEE_MISS_CHANCE = 60;

    public const MESSAGE_TYPE_INFO = 'info';

    public const MESSAGE_TYPE_ERROR = 'error';

    public const QUEEN_LIFESPAN = 100;

    public const QUEEN_STING_POINTS = 10;

    public const QUEEN_DAMAGE_POINTS = 10;

    public const QUEEN_COUNT = 1;

    public const WORKER_LIFESPAN = 75;

    public const WORKER_STING_POINTS = 5;

    public const WORKER_DAMAGE_POINTS = 25;

    public const WORKER_COUNT = 5;

    public const DRONE_LIFESPAN = 60;

    public const DRONE_STING_POINTS = 1;

    public const DRONE_DAMAGE_POINTS = 30;

    public const DRONE_COUNT = 25;
}
