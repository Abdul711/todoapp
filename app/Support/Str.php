<?php
namespace App\Support;

class Str
{
    public static function lower(string $value): string
    {
        return mb_strtolower($value);
    }

    public static function upper(string $value): string
    {
        return mb_strtoupper($value);
    }

    public static function title(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($value) <= $limit) return $value;
        return mb_substr($value, 0, $limit) . $end;
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    public static function slug(string $string, string $separator = '-'): string
    {
        $slug = preg_replace('~[^\pL\d]+~u', $separator, $string);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        return strtolower(trim($slug, $separator));
    }

    public static function random(int $length = 16): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function replace(string $search, string $replace, string $subject): string
    {
        return str_replace($search, $replace, $subject);
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }
}
