<?php

namespace Server\Request {

    use Server\Server;

    class Request {

        //Global

        private static ?Request $instance = null;
        public static function Instance(): Request {
            return static::$instance ?? (static::$instance = new static());
        }

        //Local

        private function __construct() {
            $this->requestMethod = Server::Instance()->Get('REQUEST_METHOD', 'GET');
            $this->requestUri = new RequestUri();
            $this->get = Get::Instance();
            $this->post = Post::Instance();
            $this->files = Files::Instance();
        }

        private string $requestMethod = 'GET';
        private ?RequestUri $requestUri = null;
        private ?Get $get = null;
        private ?Post $post = null;
        private ?Files $files = null;

        public function Get(string $getKey, mixed $defaultValue = ''): mixed {
            return $this->get->Get($getKey, $defaultValue);
        }

        public function Post(string $postKey, mixed $defaultValue = ''): mixed {
            return $this->post->Get($postKey, $defaultValue);
        }

        public function File(string $fileKey): array {
            return $this->files->Get($fileKey);
        }

        public function EachFile(callable $action): array {
            return $this->files->Each($action);
        }

        public function Uri(int $index = 0, string $defaultValue = ''): string {
            return $this->requestUri->Get($index, $defaultValue);
        }

        public function RequestUri(): RequestUri {
            return $this->requestUri;
        }

        public function Method(): string {
            return $this->requestMethod;
        }
    }
}
