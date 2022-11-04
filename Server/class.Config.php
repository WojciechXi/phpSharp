<?php

namespace Server {

    class Config {

        //Global

        private static ?Config $instance = null;
        public static function Instance(): Config {
            return static::$instance ?? (static::$instance = new Config());
        }

        //Local

        private function __construct() {
            $this->config = [];
        }

        private array $config = [];

        public function Get(string $configKey, string $defaultValue = null): ?string {
            return isset($this->config[$configKey]) ? $this->config[$configKey] : $defaultValue;
        }

        public function Set(string $configKey, string $configValue): string {
            return $this->config[$configKey] = $configValue;
        }
    }
}
