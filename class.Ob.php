<?php
class Ob {

    public static function Start(): bool {
        return ob_start();
    }

    public static function End(): string {
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public static function Read(callable $action): string {
        static::Start();
        $action();
        return static::End();
    }
}
