<?php

/**
 * Confirguration for the game. Adjust the values here
 * I could have used an array and can be changed while unit testing, but
 * for simplicity, I am using constants
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Config;

final class GameConfig
{
    /**
     * Maximum health points of the player
     */
    public const PLAYER_MAX_HP = 100;

    /**
     * Chance of player missing a hit
     */
    public const PLAYER_MISS_CHANCE = 10;

    /**
     * Chance of bee missing a hit
     */
    public const BEE_MISS_CHANCE = 60;

    /**
     * Message type info will print in green color in the console
     */
    public const MESSAGE_TYPE_INFO = 'info';

    /**
     * Message type error will print in red color in the console
     */
    public const MESSAGE_TYPE_ERROR = 'error';

    /**
     * Message type warning will print in yello color in the console
     */
    public const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * Queen lifespan
     */
    public const QUEEN_LIFESPAN = 100;

    /**
     * Queen sting points when hit the player
     */
    public const QUEEN_STING_POINTS = 10;

    /**
     * Queen damage points when hit by the player
     */
    public const QUEEN_DAMAGE_POINTS = 10;

    /**
     * Queen count
     */
    public const QUEEN_COUNT = 1;

    /**
     * Worker lifespan
     */
    public const WORKER_LIFESPAN = 75;

    /**
     * Worker sting points when hit the player
     */
    public const WORKER_STING_POINTS = 5;

    /**
     * Worker damage points when hit by the player
     */
    public const WORKER_DAMAGE_POINTS = 25;

    /**
     * Worker count
     */
    public const WORKER_COUNT = 5;

    /**
     * Drone lifespan
     */
    public const DRONE_LIFESPAN = 60;

    /**
     * Drone sting points when hit the player
     */
    public const DRONE_STING_POINTS = 1;

    /**
     * Drone damage points when hit by the player
     */
    public const DRONE_DAMAGE_POINTS = 30;

    /**
     * Drone count
     */
    public const DRONE_COUNT = 25;
}
