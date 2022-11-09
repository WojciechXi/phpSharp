<?php

namespace Server\Auth {

    use Server\Session;

    class Auth {

        //Global

        private static ?Auth $instance = null;
        public static function Instance(): Auth {
            return static::$instance ?? (static::$instance = new Auth());
        }

        //Local

        private function __construct() {
        }

        public function IsAuth(): bool {
            return Session::Instance()->Get('Auth.Id') != null;
        }

        public function Login(int $authId): string {
            return Session::Instance()->Set('Auth.Id', $authId);
        }

        public function Logout(): bool {
            return session_destroy();
        }
    }
}
