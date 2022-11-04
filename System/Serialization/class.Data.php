<?php

namespace System\Serialization {
    class Data {

        public static function Serialize(mixed $data): string {
            return serialize($data);
        }

        public static function Deserialize(string $data): mixed {
            return unserialize($data);
        }
    }
}
