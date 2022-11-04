<?php

namespace System {

    class Number {

        public static function ParseInt(mixed $value): int {
            return intval($value);
        }

        public static function ParseFloat(mixed $value): float {
            return floatval($value);
        }

        public static function ParseDouble(mixed $value): float {
            return doubleval($value);
        }
    }
}
