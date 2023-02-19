<?php

namespace Program {

    use System\IO\Directory;
    use Server\Response\Response;
    use Server\Routing\Route;
    use Server\Session;
    use Server\Server;

    class Program {

        //Global

        private static ?Program $instance = null;
        public static final function Instance(): Program {
            return static::$instance ?? (static::$instance = new static());
        }

        //Local

        private function __construct() {
        }

        private array $paths = [];

        public final function SetPath(string $name, string $path): string {
            return $this->paths[$name] = $path;
        }

        public final function GetPath(string $name, string $defaultPath = ''): string {
            return isset($this->paths[$name]) ? $this->paths[$name] : $defaultPath;
        }

        public final function GetUrl(string $name = null): string {
            $server = Server::Instance();
            $requestScheme = $server->Get('REQUEST_SCHEME');
            //$requestScheme = $server->Get('HTTPS', 'on') == 'on' ? 'https' : 'http';
            $httpHost = $server->Get('HTTP_HOST');
            return "{$requestScheme}://{$httpHost}/{$name}";
        }

        protected function Preload(): void {
            $this->SetPath('Public', __DIR__ . '/Public');
            $this->SetPath('Storage', __DIR__ . '/Public/Storage');

            $this->SetPath('Config', __DIR__ . '/Config');
            $this->SetPath('Routes', __DIR__ . '/Routes');
            $this->SetPath('Views', __DIR__ . '/Views');
            $this->SetPath('Controllers', __DIR__ . '/Controllers');
            $this->SetPath('Models', __DIR__ . '/Models');
            $this->SetPath('Application', __DIR__ . '/Application');
        }

        protected function Load(): void {
        }

        public final function Main(): void {
            $this->Preload();
            $this->Load();
            Session::Instance()->Start();
            Route::Execute();
            Response::Instance()->Send();
        }
    }
}
