<?php

/**
 * ConsoleUi implements IConsoleUi interface
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

namespace KVP\Beesinthetrap\Services\Providers;

use KVP\Beesinthetrap\Contracts\IConsoleUi;

use function Laravel\Prompts\note;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class LaravelPromptProvider implements IConsoleUi
{
    /**
     * text - wrapper for Laravel Prompts text
     */
    public function text(string $label, string $placeholder = '', bool|string $required = false, string $default = '', string $hint = ''): string
    {
        return text(
            label: $label,
            placeholder: $placeholder,
            required: $required,
            default: $default,
            hint: $hint
        );
    }

    /**
     * select - wrapper for Laravel Prompts select
     */
    public function select(string $label, array $options, string $hint = ''): string
    {
        return select(
            label: $label,
            options: $options,
            hint: $hint
        );
    }

    /**
     * note - wrapper for Laravel Prompts note
     */
    public function note(string $message, ?string $type = null): void
    {
        note(message: $message, type: $type);
    }

    /**
     * table - wrapper for Laravel Prompts table
     */
    public function table(array $headers, array $rows): void
    {
        table($headers, $rows);
    }
}
