<?php

namespace Server\Validation {

    class Validator {

        //Global

        private static ?Validator $instance = null;
        public static function Instance(): Validator {
            return static::$instance ?? (static::$instance = new Validator());
        }

        //Local

        private function __construct() {
        }
    }
}
