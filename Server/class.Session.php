<?php

namespace Server {

    use SessionHandlerInterface;

    class Session implements SessionHandlerInterface {

        //Global

        private static ?Session $instance = null;
        public static function Instance(): Session {
            return static::$instance ?? (static::$instance = new Session());
        }

        //Local

        private function __construct() {
            session_set_save_handler($this, true);
        }

        public function Start() {
            session_start();
        }

        public function Get(string $sessionKey, string $defaultValue = null): ?string {
            return isset($_SESSION[$sessionKey]) ? $_SESSION[$sessionKey] : $defaultValue;
        }

        public function Set(string $sessionKey, string $sessionValue): string {
            return $_SESSION[$sessionKey] = $sessionValue;
        }

        function open(string $path, string $name): bool {
            return Database::Instance()->Connect();
        }

        function close(): bool {
            return Database::Instance()->Close();
        }

        function read(string $id): string|false {
            $now = date('Y-m-d H:i:s');
            $session = Database::Instance()->Object("SELECT data FROM sessions WHERE id = '{$id}' AND access > '{$now}' LIMIT 1");
            return $session ? $session->data : '';
        }

        function write(string $id, string $data): bool {
            $now = date('Y-m-d H:i:s');
            $newDateTime = date('Y-m-d H:i:s', strtotime($now . ' + 1 hour'));
            return Database::Instance()->Bool("REPLACE INTO sessions SET id = '{$id}', access = '{$newDateTime}', data = '{$data}'");
        }

        function destroy(string $id): bool {
            return Database::Instance()->Bool("DELETE FROM sessions WHERE id = '{$id}' LIMIT 1");
        }

        function gc(int $max_lifetime): int|false {
            return Database::Instance()->Bool("DELETE FROM sessions WHERE ((UNIX_TIMESTAMP(access) + {$max_lifetime})");
        }
    }
}
