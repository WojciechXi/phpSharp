<?php

namespace System\IO {

    class Directory {

        public static function Exists(string $path): bool {
            return file_exists($path) && is_dir($path);
        }

        public static function Delete(string $path): bool {
            if (!static::Exists($path)) return false;
            return unlink($path);
        }

        //Local

        public function __construct(string $path) {
            $this->path = str_replace('\\', '/', $path);
        }

        private string $path = '';

        private function GetItems(): array {
            return file_exists($this->path) ? array_diff(scandir($this->path), ['.', '..']) : [];
        }

        public function GetDirectories(array $directories = []): array {
            $items = $this->GetItems();
            foreach ($items as $item) {
                $itemPath = implode('/', [$this->path, $item]);
                if (!is_dir($itemPath)) continue;
                $directories[] = new Directory($itemPath);
            }

            return $directories;
        }

        public function GetFiles(bool $recursive = false, array $extensions = [], array $files = []): array {
            $items = $this->GetItems();
            foreach ($items as $item) {
                $itemPath = implode('/', [$this->path, $item]);
                if (is_dir($itemPath)) continue;
                if ($extensions) {
                    $extension = pathinfo($item)['extension'];
                    if (in_array($extension, $extensions)) $files[] = new File($itemPath);
                } else {
                    $files[] = new File($itemPath);
                }
            }

            if ($recursive) {
                $directories = $this->GetDirectories();
                foreach ($directories as $directory) $files = $directory->GetFiles(true, $extensions, $files);
            }

            return $files;
        }
    }
}
