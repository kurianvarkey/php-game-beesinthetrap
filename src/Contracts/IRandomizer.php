<?php

/**
 * Interface for the randomizer and is used for the testing
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Contracts;

interface IRandomizer
{
    public function random(int $min, int $max): int;

    public function arrayRand(array $array): int|string;
}
