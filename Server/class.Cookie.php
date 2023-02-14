<?php

namespace Server {

    class Cookie {

        //Global

        private static ?Cookie $instance = null;
        public static function Instance(): Cookie {
            return static::$instance ?? (static::$instance = new Cookie());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $cookieKey, string $defaultValue = null): ?string {
            return isset($_COOKIE[$cookieKey]) ? $_COOKIE[$cookieKey] : $defaultValue;
        }

        public function Set(string $cookieKey, string $cookieValue): bool {
            return setcookie($cookieKey, $cookieValue, 0, '/');
        }
    }
}
