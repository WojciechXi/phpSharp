<?php

namespace Server {

    use Exception;

    class Ob {

        public static function Read(callable $action): string {
            ob_start();
            try {
                $action();
            } catch (Exception $exception) {
                print_r($exception);
            }
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
    }
}
