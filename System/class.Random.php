<?php

namespace System {
    class Random {

        public static function Float(): float {
            return mt_rand() / mt_getrandmax();
        }

        public static function RangeInt(int $min, int $max): int {
            return mt_rand($min, $max);
        }

        public static function Range(float $min, float $max): float {
            return Math::Lerp($min, $max, static::Float());
        }
    }
}
