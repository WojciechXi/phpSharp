<?php

namespace System {

    class Str {

        public static string $empty = '';

        function Empty(): string {
            return static::$empty;
        }

        function IsEmptyOrNull(string $string = null): bool {
            return !$string || empty($string);
        }

        function Length(string $string): int {
            return mb_strlen($string);
        }

        function Replace(string $string, string $search, string $replace): string {
            return str_replace($search, $replace, $string);
        }

        function Trim(string $string): string {
            return trim($string);
        }

        function StartsWith(string $string, string $startsWith): bool {
            return str_starts_with($string, $startsWith);
        }

        function EndsWith(string $string, string $endsWith): bool {
            return str_ends_with($string, $endsWith);
        }

        function Contains(string $string, string $contains): bool {
            return str_contains($string, $contains);
        }

        function Pos(string $string, string $word): int|false {
            return mb_strpos($string, $word);
        }
    }
}
