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

        public function GetUrl(string $item): string {
            return Program::Instance()->GetUrl("Storage/{$item}");
        }

        public function GetPath(): string {
            return Program::Instance()->GetPath('Storage');
        }

        public function Save(array $file): ?object {
            $pathInfo = pathinfo($file['name']);

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
