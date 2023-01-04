<?php

namespace Server\Request {

    use Server\Server;

    class RequestUri {

        public function __construct(string $requestUri = null) {
            $this->requestUri = $requestUri ? $requestUri : Server::Instance()->Get('REQUEST_URI', '/');
        }

        private string $requestUri = '';

        public function RequestUri(): string {
            return $this->requestUri;
        }

        public function Get(int $index = 0, string $defaultValue = null): ?string {
            $requestUri = explode('?', $this->requestUri)[0];
            $requestUri = explode('/', $requestUri);
            $requestUri = array_diff($requestUri, ['']);
            $requestUri = array_values($requestUri);
            return isset($requestUri[$index]) ? $requestUri[$index] : $defaultValue;
        }

        public function Length(): int {
            $requestUri = explode('?', $this->requestUri)[0];
            $requestUri = explode('/', $requestUri);
            $requestUri = array_values($requestUri);
            $requestUri = array_diff($requestUri, ['']);
            return count($requestUri);
        }
    }
}
