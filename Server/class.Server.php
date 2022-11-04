<?php

namespace Server {

    class Server {

        //Global

        private static ?Server $instance = null;
        public static function Instance(): Server {
            return static::$instance ?? (static::$instance = new Server());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $serverKey, string $defaultValue = null): ?string {
            return isset($_SERVER[$serverKey]) ? $_SERVER[$serverKey] : $defaultValue;
        }

        public function Set(string $serverKey, string $serverValue): string {
            return $_SERVER[$serverKey] = $serverValue;
        }
    }
}
