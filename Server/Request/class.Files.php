<?php

namespace Server\Request {

    class Files {

        //Global

        private static ?Files $instance = null;
        public static function Instance(): Files {
            return static::$instance ?? (static::$instance = new Files());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $filesKey, string $defaultValue = null): ?string {
            return isset($_FILES[$filesKey]) ? $_FILES[$filesKey] : $defaultValue;
        }

        public function Each(callable $action): array {
            $files = [];
            foreach ($_FILES as $key => $file) $files[] = $action($file, $key);
            return $files;
        }
    }
}
