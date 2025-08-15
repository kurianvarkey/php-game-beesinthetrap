<?php

/**
 * Randomizer class
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Services;

use KVP\Beesinthetrap\Contracts\IRandomizer;

final class Randomizer implements IRandomizer
{
    /**
     * Generate a random number between min and max (inclusive)
     */
    public function random(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * Get a random array key from the given array
     */
    public function arrayRand(array $array): int|string
    {
        return array_rand($array);
    }
}
