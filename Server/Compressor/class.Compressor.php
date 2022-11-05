<?php

namespace Server\Compressor {

    use System\IO\Directory;

    class Compressor {

        public function __construct(string $path) {
            $this->path = $path;
        }

        private string $path = '';

        public function Compress(callable $action = null): string {
            $compressed = [];
            $directory = new Directory($this->path);
            $files = $directory->GetFiles(true);
            foreach ($files as $file) $compressed[] = $file->ReadAllText();
            $compressed = implode("\n\n", $compressed);
            if ($action) $compressed = $action($compressed);
            return $compressed;
        }
    }
}
