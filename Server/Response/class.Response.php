<?php

namespace Server\Response {

    class Response {

        //Global

        private static ?Response $instance = null;
        public static function Instance(): Response {
            return static::$instance ?? (static::$instance = new Response());
        }

        //Local

        private function __construct() {
        }

        private mixed $content = null;
        private string $contentType = 'text/html';
        private string $redirect = '';
        private int $responseCode = 200;

        public function Header(string $key, string $value): self {
            header("{$key}: {$value}");
            return $this;
        }

        public function Content(mixed $content): self {
            $this->content = $content;
            return $this;
        }

        public function ContentType(string $contentType): self {
            $this->contentType = $contentType;
            return $this;
        }

        public function ResponseCode(int $responseCode): self {
            $this->responseCode = $responseCode;
            return $this;
        }

        public function Json(mixed $content): self {
            return $this->ContentType('application/json')->Content($content);
        }

        public function Html(string $content): self {
            return $this->ContentType('text/html')->Content($content);
        }

        public function Redirect(string $redirect = '/'): self {
            $this->redirect = $redirect;
            return $this;
        }

        public function Send(): void {
            http_response_code($this->responseCode);
            $this->Header('Content-type', $this->contentType);
            if ($this->redirect) $this->Header('Location', $this->redirect);

            $content = $this->content;
            if (is_object($content) || is_array($content)) $content = json_encode($content);

            die($content);
        }
    }
}
