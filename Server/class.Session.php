<?php

namespace Server {

    class Session {

        //Global

        private static ?Session $instance = null;
        public static function Instance(): Session {
            return static::$instance ?? (static::$instance = new Session());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $sessionKey, string $defaultValue = null): ?string {
            return isset($_SESSION[$sessionKey]) ? $_SESSION[$sessionKey] : $defaultValue;
        }

        public function Set(string $sessionKey, string $sessionValue): string {
            return $_SESSION[$sessionKey] = $sessionValue;
        }
    }
}
