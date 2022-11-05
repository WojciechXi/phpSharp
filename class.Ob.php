<?php

namespace Server {

    use Exception;

    class Ob {

        public static function Read(callable $action, array $replace = []): string {
            ob_start();
            try {
                $action();
            } catch (Exception $exception) {
                print_r($exception);
            }
            $contents = ob_get_contents();
            ob_end_clean();
            foreach ($replace as $key => $value) $contents = str_replace('{{' . $key . '}}', $value, $contents);
            return $contents;
        }
    }
}
