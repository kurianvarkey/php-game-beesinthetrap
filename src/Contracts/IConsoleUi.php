<?php

/**
 * Interface for the console UI and is used for the testing
 *
 * @version  1.0.0
 *
 * @author   K V P <kurianvarkey@yahoo.com>
 */

declare(strict_types=1);

namespace KVP\Beesinthetrap\Contracts;

interface IConsoleUi
{
    public function text(string $label, string $placeholder = '', bool|string $required = false, string $default = '', string $hint = ''): string;

    public function select(string $label, array $options, string $hint = ''): string;

    public function note(string $message, ?string $type = null): void;

    public function table(array $headers, array $rows): void;

    public function clear(): void;
}
