<?php

namespace Server\Request {

    class Get {

        //Global

        private static ?Get $instance = null;
        public static function Instance(): Get {
            return static::$instance ?? (static::$instance = new Get());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $getKey, string $defaultValue = null): ?string {
            return isset($_GET[$getKey]) ? $_GET[$getKey] : $defaultValue;
        }

        public function Each(callable $action): array {
            $get = [];
            foreach ($_GET as $key => $value) $get[$key] = $action($value, $key);
            return $get;
        }
    }
}
