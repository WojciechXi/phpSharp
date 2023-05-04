<?php

namespace Server\View {

    use Ob;
    use Program\Program;
    use Server\Auth\Auth;
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
            $this->params = [
                'auth' => Auth::Instance(),
                'config' => Config::Instance(),
                'program' => Program::Instance(),
                'request' => Request::Instance(),
                'server' => Server::Instance(),
                'session' => Session::Instance(),
            ];
        }

        private array $params = [];

        public function GetPath(string $view = null): string {
            return Program::Instance()->GetPath($view ? $view : 'Views');
        }

        public function Load(string $name, array $params = [], string $view = null): ?string {
            $params = array_merge($this->params, $params);
            $this->params = $params;

            $path = $this->GetPath($view) . '/' . $name . '.php';

            if (File::Exists($path)) {
                $buffer = Ob::Read(function () use ($path, $params) {
                    extract($params);
                    require $path;
                });

                return $buffer;
            }
            return null;
        }

        public function Write(mixed $variable) {
            if (is_array($variable)) $variable = implode('', $variable);
            echo $variable;
        }
    }
}
