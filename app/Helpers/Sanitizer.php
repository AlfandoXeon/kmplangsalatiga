<?php

namespace App\Helpers;

class Sanitizer
{
    public static function escape(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        return empty($text) ? 'item-' . time() : $text;
    }

    public static function cleanInput(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = self::cleanInput($val);
            }
            return $data;
        }

        if (is_string($data)) {
            return trim($data);
        }

        return $data;
    }
}

