<?php

namespace Server\Request {

    use Server\Server;

    class Request {

        //Global

        private static ?Request $instance = null;
        public static function Instance(): Request {
            return static::$instance ?? (static::$instance = new Request());
        }

        //Local

        private function __construct() {
            $this->requestMethod = Server::Instance()->Get('REQUEST_METHOD', 'GET');
            $this->requestUri = new RequestUri();
            $this->get = new Get();
            $this->post = new Post();
        }

        private string $requestMethod = 'GET';
        private ?RequestUri $requestUri = null;
        private ?Get $get = null;
        private ?Post $post = null;
    }
}
