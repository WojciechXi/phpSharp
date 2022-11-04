<?php

namespace Server {

    class Storage {

        //Global

        private static ?Storage $instance = null;
        public static function Instance(): Storage {
            return static::$instance ?? (static::$instance = new Storage());
        }

        //Local

        private function __construct() {
        }
    }
}
