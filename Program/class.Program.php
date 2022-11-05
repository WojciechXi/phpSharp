<?php

namespace Program {

    use System\IO\Directory;
    use Server\Response\Response;
    use Server\Routing\Route;
    use Server\Session;

    class Program {

        //Global

        private static ?Program $instance = null;
        public static final function Instance(): Program {
            return static::$instance ?? (static::$instance = new static());
        }

        //Local

        private function __construct() {
            $this->Preload();
            $this->Load();
        }

        private array $paths = [];

        protected final function SetPath(string $name, string $path): string {
            return $this->paths[$name] = $path;
        }

        public final function GetPath(string $name, string $defaultPath = ''): string {
            return isset($this->paths[$name]) ? $this->paths[$name] : $defaultPath;
        }

        protected function Preload(): void {
            $this->SetPath('Public', __DIR__ . '/Public');
            $this->SetPath('Storage', __DIR__ . '/Public/Storage');

            $this->SetPath('Styles', __DIR__ . '/Public/Styles');
            $this->SetPath('Scripts', __DIR__ . '/Public/Scripts');

            $this->SetPath('Config', __DIR__ . '/Config');
            $this->SetPath('Routes', __DIR__ . '/Routes');
            $this->SetPath('Views', __DIR__ . '/Views');
            $this->SetPath('Application', __DIR__ . '/Application');
        }

        protected function Load(): void {
            $applicationDirectory = new Directory($this->GetPath('Application'));
            $applicationFiles = $applicationDirectory->GetFiles(true, ['php']);
            foreach ($applicationFiles as $applicationFile) $applicationFile->RequireOnce();

            $configDirectory = new Directory($this->GetPath('Config'));
            $configFiles = $configDirectory->GetFiles(true, ['php']);
            foreach ($configFiles as $configFile) $configFile->RequireOnce();

            $routesDirectory = new Directory($this->GetPath('Routes'));
            $routesFiles = $routesDirectory->GetFiles(true, ['php']);
            foreach ($routesFiles as $routesFile) $routesFile->RequireOnce();
        }

        public final function Main(): void {
            Session::Instance()->Start();
            Route::Execute();
            Response::Instance()->Send();
        }
    }
}
