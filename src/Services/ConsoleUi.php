<?php

/**
 * ConsoleUi implements IConsoleUi interface
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

namespace KVP\Beesinthetrap\Services;

use KVP\Beesinthetrap\Contracts\IConsoleUi;
use KVP\Beesinthetrap\Services\Providers\LaravelPromptProvider;

class ConsoleUi
{
    /**
     * __construct
     */
    public function __construct(
        public ?IConsoleUi $consoleUiProvider = null
    ) {
        $this->consoleUiProvider ??= new LaravelPromptProvider;
    }

    /**
     * text - wrapper for Laravel Prompts text
     */
    public function text(string $label, string $placeholder = '', bool|string $required = false, string $default = '', string $hint = ''): string
    {
        return $this->consoleUiProvider->text(
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
        return $this->consoleUiProvider->select(
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
        $this->consoleUiProvider->note(message: $message, type: $type);
    }

    /**
     * table - wrapper for Laravel Prompts table
     */
    public function table(array $headers, array $rows): void
    {
        $this->consoleUiProvider->table($headers, $rows);
    }

    /**
     * clear - wrapper for Laravel Prompts clear
     */
    public function clear(): void
    {
        $this->consoleUiProvider->clear();
    }
}
