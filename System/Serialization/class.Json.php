<?php

namespace System\Serialization {

    class Json {

        public static function Serialize(mixed $data): string {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
        }

        public static function Deserialize(string $json): mixed {
            return json_decode($json);
        }
    }
}
