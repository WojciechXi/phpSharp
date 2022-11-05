<?php

namespace Server\View {

    use Ob;
    use Program\Program;
    use Server\Config;
    use Server\Server;
    use Server\Session;
    use System\IO\File;
    use Server\Request\Request;

    class View {

        //Global

        private static ?View $instance = null;
        public static final function Instance(): View {
            return static::$instance ?? (static::$instance = new static());
        }

        //Local

        private function __construct() {
        }

        public function GetPath(): string {
            return Program::Instance()->GetPath('Views');
        }

        public function Load(string $name, array $replace = [], array $params = []): ?string {
            $path = $this->GetPath() . '/' . $name . '.php';
            if (File::Exists($path)) {
                $buffer = Ob::Read(function () use ($path, $params) {
                    $config = Config::Instance();
                    $program = Program::Instance();
                    $request = Request::Instance();
                    $server = Server::Instance();
                    $session = Session::Instance();
                    foreach ($params as $key => $value) eval("{$key} = {$value};");
                    require $path;
                });

                foreach ($replace as $key => $value) $buffer = str_replace('{{' . $key . '}}', $value, $buffer);

                return $buffer;
            }
            return null;
        }
    }
}
