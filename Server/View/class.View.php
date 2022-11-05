<?php

namespace Server\View {

    use Application;
    use System\IO\File;
    use Server\Ob;
    use Server\Request\Request;
    use Server\Server;
    use Server\Session;
    use Program\Program;

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
            return Application::Instance()->GetPath('Views');
        }

        public function Load(string $name, array $replace = [], array $params = []): ?string {
            $path = $this->GetPath() . '/' . $name . '.php';
            if (File::Exists($path)) {
                $buffer = Ob::Read(function () use ($path, $params) {
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
