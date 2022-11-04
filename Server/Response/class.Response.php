<?php

namespace Server\Response {

    class Response {

        //Global

        private static ?Response $instance = null;
        public static function Instance(): Response {
            return static::$instance ?? (static::$instance = new Response());
        }

        //Local

        private function __construct() {
        }
    }
}
