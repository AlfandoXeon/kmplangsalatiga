<?php

declare(strict_types=1);

/**
 * Global Helper Functions
 * Website K'mplang Salatiga
 */

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return \App\Helpers\Sanitizer::escape($value);
    }
}
