<?php

namespace Server {

    use mysqli;
    use mysqli_result;

    class Database {

        //Global

        private static ?Database $instance = null;
        public static function Instance(): Database {
            return static::$instance ?? (static::$instance = new Database());
        }

        //Local

        private function __construct() {
        }

        private ?mysqli $mysqli = null;

        public function RealEscapeString(string $string = null): string {
            return mysqli_real_escape_string($this->mysqli, $string ?? '');
        }

        public function Connect(): bool {
            $host = Config::Instance()->Get('Database.Host');
            $user = Config::Instance()->Get('Database.User');
            $password = Config::Instance()->Get('Database.Password');
            $database = Config::Instance()->Get('Database.Database');
            $charset = Config::Instance()->Get('Database.Charset', 'utf8mb4');
            $this->mysqli = new mysqli($host, $user, $password, $database);
            $this->mysqli->set_charset($charset);
            return true;
        }

        public function Close(): bool {
            if (!$this->mysqli) return false;
            return $this->mysqli->close();
        }

        public function Query(string $sql): mysqli_result | bool {
            return $this->mysqli->query($sql);
        }

        public function Bool(string $sql): bool {
            return boolval($this->mysqli->query($sql));
        }

        public function Insert(string $sql): int {
            $this->mysqli->query($sql);
            return $this->mysqli->insert_id;
        }

        public function Object(string $sql): ?object {
            $results = $this->Query($sql);
            if ($results && $object = mysqli_fetch_object($results)) return $object;
            return null;
        }

        public function Objects(string $sql): array {
            $objects = [];
            $results = $this->Query($sql);
            while ($results && $object = mysqli_fetch_object($results)) $objects[] = $object;
            return $objects;
        }
    }
}
