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
        }

        private string $requestMethod = 'GET';
        private ?RequestUri $requestUri = null;
        private ?Get $get = null;
        private ?Post $post = null;

        public function Get(string $getKey, string $defaultValue = ''): string {
            return $this->get->Get($getKey, $defaultValue);
        }

        public function Post(string $postKey, string $defaultValue = ''): string {
            return $this->post->Get($postKey, $defaultValue);
        }

        public function Uri(int $index = 0, string $defaultValue = ''): string {
            return $this->requestUri->Get($index, $defaultValue);
        }
    }
}
