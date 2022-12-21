<?php

namespace Server\Request {

    class Post {

        //Global

        private static ?Post $instance = null;
        public static function Instance(): Post {
            return static::$instance ?? (static::$instance = new Post());
        }

        //Local

        private function __construct() {
        }

        public function Get(string $postKey, mixed $defaultValue = null): mixed {
            return isset($_POST[$postKey]) ? $_POST[$postKey] : $defaultValue;
        }

        public function Each(callable $action): array {
            $post = [];
            foreach ($_POST as $key => $value) $post[$key] = $action($value, $key);
            return $post;
        }
    }
}
