<?php

/**
 * Enum for the player options
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Enums;

enum PlayerOption: string
{
    case Hit = 'hit';
    case Auto = 'auto';
    case Quit = 'quit';

    /**
     * Returns the display hints for the player input.
     *
     * @return string The display hint for the player input.
     */
    public function displayHints(): string
    {
        return match ($this) {
            self::Hit => 'Hit',
            self::Auto => 'Auto (perform on your behalf)',
            self::Quit => 'Quit',
            default => 'Invalid option',
        };
    }
}
