<?php

namespace System\Serialization {
    class Base64 {

        public static function Serialize(string $data): string {
            return base64_encode($data);
        }

        public static function Deserialize(string $data): string {
            return base64_decode($data, true);
        }
    }
}
