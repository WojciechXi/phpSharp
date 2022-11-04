<?php

namespace System {
    class Math {

        public static function Abs(float $value): float {
            return abs($value);
        }

        public static function Floor(float $value): float {
            return floor($value);
        }

        public static function Ceil(float $value): float {
            return ceil($value);
        }

        public static function Round(float $value, int $decimal = 0): float {
            return round($value, $decimal);
        }

        public static function Lerp(float $left, float $right, float $transition): float {
            return $left + ($right - $left) * $transition;
        }

        public static function Clamp(float $value, float $min, float $max): float {
            if ($value < $min) return $min;
            if ($value > $max) return $max;
            return $value;
        }
    }
}
