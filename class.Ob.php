<?php

namespace Server {

    class Ob {

        public static function Read(callable $action): string {
            ob_start();
            $action();
            $content = ob_get_contents();
            ob_end_flush();
            return $content;
        }
    }
}
