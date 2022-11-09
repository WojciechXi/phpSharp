<?php

namespace Server\Database {

    use Server\Database;

    class DatabaseObject {

        public static string $table = '';

        public static function Insert(array $data = []): ?static {
            $table = static::$table;

            $keys = [];
            $values = [];
            foreach ($data as $key => $value) {
                $keys[] = $key;
                $values[] = "'{$value}'";
            }
            $keys = implode(', ', $keys);
            $values = implode(', ', $values);

            $id = Database::Instance()->Insert("INSERT INTO {$table} ( {$keys} ) VALUES ( {$values} )");

            return static::ById($id);
        }

        public static function Object(string $where = null): ?static {
            $table = static::$table;
            $object = Database::Instance()->Object("SELECT * FROM {$table} {$where} LIMIT 1");
            return $object ? new static($object) : null;
        }

        public static function Objects(string $where = null): array {
            $table = static::$table;
            $objects = Database::Instance()->Objects("SELECT * FROM {$table} {$where}");
            foreach ($objects as $key => $object) $objects[$key] = new static($object);
            return $objects;
        }

        public static function Last(int $limit = 9): array {
            return static::Objects("ORDER BY dateOfUpdate DESC LIMIT {$limit}");
        }

        public static function Random(int $limit = 5): array {
            return static::Objects("ORDER BY RAND() LIMIT {$limit}");
        }

        public static function ById(int $id): ?static {
            return static::Object("WHERE id = '{$id}'");
        }

        public static function ObjectsById(string|array $ids): array {
            if (!$ids || !is_array($ids)) return [];
            $ids = implode(', ', $ids);
            return static::Objects("WHERE id IN ( {$ids} )");
        }

        public static function ObjectOrInsert(string $where = null, array $data = []): ?static {
            $object = static::Object($where);
            return $object ? $object : static::Insert($data);
        }

        //Local

        public function __construct(object $object = null) {
            $this->SetObject($object);
        }

        public int $id = 0;
        public string $dateOfUpdate = '';
        public string $dateOfCreate = '';

        public function __get(string $propertyName) {
            if ($propertyName == 'Class') return get_class($this);
        }

        private function SetObject(object $object = null): void {
            foreach ($this as $key => $value) $this->$key = isset($object->$key) && $object->$key ? $object->$key : $value;
        }

        public function Update(array $data = []): bool {
            $table = static::$table;
            foreach ($data as $key => $value) $data[$key] = "{$key} = '{$value}'";
            $data = implode(', ', $data);
            if (Database::Instance()->Bool("UPDATE {$table} SET {$data} WHERE id = '{$this->id}' LIMIT 1")) {
                $object = Database::Instance()->Object("SELECT * FROM {$table} WHERE id = '{$this->id}'");
                $this->SetObject($object);
                return true;
            }
            return false;
        }

        public function Delete(): bool {
            $table = static::$table;
            return Database::Instance()->Bool("DELETE FROM {$table} WHERE id = '{$this->id}' LIMIT 1");
        }
    }
}
