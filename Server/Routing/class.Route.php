<?php

namespace Server\Routing {

    use ReflectionFunction;
    use ReflectionMethod;
    use Server\Auth\Auth;
    use Server\Config;
    use Server\Request\Files;
    use Server\View\View;
    use Server\Request\Params;
    use Server\Request\Request;
    use Server\Request\RequestUri;
    use Server\Response\Response;
    use Server\Storage;
    use Server\Validation\Validator;

    class Route {

        private static array $routes = [];

        public static function Get(string $uri, string | callable $callback, string $method = ''): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('GET', $requestUri, $callback, $method);
        }

        public static function Post(string $uri, string | callable $callback, string $method = ''): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('POST', $requestUri, $callback, $method);
        }

        public static function Put(string $uri, string | callable $callback, string $method = ''): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('PUT', $requestUri, $callback, $method);
        }

        public static function Delete(string $uri, string | callable $callback, string $method = ''): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('DELETE', $requestUri, $callback, $method);
        }

        public static function Execute(Request $request = null) {
            if (!$request) $request = Request::Instance();
            foreach (static::$routes as $route) {
                if (!$route->VerifyMethod($request->Method())) continue;
                if (!$route->VerifyUri($request->RequestUri())) continue;
                return $route->Call($request);
            }
        }

        //Local

        public function __construct(string $requestMethod, RequestUri $requestUri, string|callable $callback, string $method = '') {
            $this->requestMethod = $requestMethod;
            $this->requestUri = $requestUri;
            $this->callback = $callback;
            $this->method = $method;
        }

        private string $requestMethod = 'GET';
        private ?RequestUri $requestUri = null;
        private mixed $callback = null;
        private string $method = '';

        public function VerifyMethod(string $requestMethod): bool {
            return $this->requestMethod == $requestMethod;
        }

        public function VerifyUri(RequestUri $requestUri): bool {
            if ($this->requestUri->Get() == '*') return true;
            if ($this->requestUri->Length() != $requestUri->Length()) return false;
            $length = $this->requestUri->Length();
            for ($i = 0; $i < $length; $i++) {
                $left = $this->requestUri->Get($i);
                if (str_starts_with($left, ':')) continue;
                $right = $requestUri->Get($i);
                if ($left != $right) return false;
            }
            return true;
        }

        public function Call(Request $request): mixed {
            $params = new Params($this->requestUri, $request->RequestUri());

            if (is_string($this->callback)) {
                $method = $params->Get('method', $this->method);

                $controller = new ($this->callback)();
                $reflectionMethod = new ReflectionMethod($controller, $method);

                return call_user_func_array([$controller, $method], $this->GetArguments($request, $params, $reflectionMethod->getParameters()));
            } else {
                $reflectionFunction = new ReflectionFunction($this->callback);
                return call_user_func_array($this->callback, $this->GetArguments($request, $params, $reflectionFunction->getParameters()));
            }
        }

        private function GetArguments(Request $request, Params $params, array $parameters, array $arguments = []): array {
            foreach ($parameters as $parameter) {
                if ($parameter->getName() == 'auth') $arguments['auth'] = Auth::Instance();
                if ($parameter->getName() == 'validator') $arguments['validator'] = Validator::Instance();
                if ($parameter->getName() == 'response') $arguments['response'] = Response::Instance();
                if ($parameter->getName() == 'config') $arguments['config'] = Config::Instance();
                if ($parameter->getName() == 'files') $arguments['files'] = Files::Instance();
                if ($parameter->getName() == 'view') $arguments['view'] = View::Instance();
                if ($parameter->getName() == 'storage') $arguments['storage'] = Storage::Instance();
                if ($parameter->getName() == 'request') $arguments['request'] = $request;
                if ($parameter->getName() == 'params') $arguments['params'] = $params;
            }
            return $arguments;
        }
    }
}
