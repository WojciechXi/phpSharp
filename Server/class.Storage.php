<?php

namespace Server {

    use Program\Program;

    class Storage {

        //Global

        private static ?Storage $instance = null;
        public static function Instance(): Storage {
            return static::$instance ?? (static::$instance = new Storage());
        }

        //Local

        private function __construct() {
        }

        public function GetUrl(string $item = null): string {
            return Program::Instance()->GetUrl("Storage/{$item}");
        }

        public function GetPath(string $item = null): string {
            if ($item) return Program::Instance()->GetPath("Storage") . "/{$item}";
            return Program::Instance()->GetPath("Storage");
        }

        public function Save(array $file = null): ?object {
            if (!$file) return null;

            $pathInfo = pathinfo($file['name']);
            if (!$pathInfo) return null;
            if (!isset($pathInfo['extension'])) return null;

            $fileName = $pathInfo['filename'];
            $fileExtension = $pathInfo['extension'];

            $fileAlias = md5(microtime() . $fileName .  $fileExtension);
            $filePath = $this->GetPath() . "/{$fileAlias}.{$fileExtension}";

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                return (object)[
                    'name' => $fileName,
                    'extension' => $fileExtension,
                    'alias' => $fileAlias,
                ];
            }

            return null;
        }
    }
}
