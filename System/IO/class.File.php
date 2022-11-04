<?php

namespace System\IO {

    class File {

        public static function Exists(string $path): bool {
            return file_exists($path) && is_file($path);
        }

        public static function Delete(string $path): bool {
            if (!static::Exists($path)) return false;
            return unlink($path);
        }

        //Local

        public function __construct(string $path) {
            $this->path = $path;
        }

        private string $path = '';

        public function ReadAllText(): string {
            return file_get_contents($this->path);
        }

        public function WriteAllText(string $text): int | false {
            return file_put_contents($this->path, $text);
        }
    }
}
