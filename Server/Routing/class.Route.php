<?php

namespace Server\Routing {

    use ReflectionFunction;
    use ReflectionMethod;
    use Server\Auth\Auth;
    use Server\Config;
    use Server\Database\DatabaseObject;
    use Server\Request\Files;
    use Server\View\View;
    use Server\Request\Params;
    use Server\Request\Request;
    use Server\Request\RequestUri;
    use Server\Response\Response;
    use Server\Storage;
    use Server\Validation\Validator;

    class Route {

        public static function Debug(): void {
            print_r(static::$routes);
        }

        private static array $routes = [];

        public static function Get(string $uri, string | callable $callback, string $method = ''): ?Route {
            return static::Route('GET', $uri, $callback, $method);
        }

        public static function Post(string $uri, string | callable $callback, string $method = ''): ?Route {
            return static::Route('POST', $uri, $callback, $method);
        }

        public static function Put(string $uri, string | callable $callback, string $method = ''): ?Route {
            return static::Route('PUT', $uri, $callback, $method);
        }

        public static function Delete(string $uri, string | callable $callback, string $method = ''): ?Route {
            return static::Route('DELETE', $uri, $callback, $method);
        }

        private static function Route(string $requestMethod, string $uri, string | callable $callback, string $method = ''): ?Route {
            $requestUri = new RequestUri($uri);
            $route = new Route($requestMethod, $requestUri, $callback, $method);
            array_push(static::$routes, $route);
            return $route;
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

        protected function __construct(string $requestMethod, RequestUri $requestUri, string|callable $callback, string $method = '') {
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
                $type = strval($parameter->getType());
                $type = str_replace('?', '', $type);

                if ($type == Auth::class) {
                    $arguments['auth'] = Auth::Instance();
                    continue;
                }

                if ($type == Validator::class) {
                    $arguments['validator'] = Validator::Instance();
                    continue;
                }

                if ($type == Response::class) {
                    $arguments['response'] = Response::Instance();
                    continue;
                }

                if ($type == Config::class) {
                    $arguments['config'] = Config::Instance();
                    continue;
                }

                if ($type == Files::class) {
                    $arguments['files'] = Files::Instance();
                    continue;
                }

                if ($type == View::class) {
                    $arguments['view'] = View::Instance();
                    continue;
                }

                if ($type == Storage::class) {
                    $arguments['storage'] = Storage::Instance();
                    continue;
                }

                if ($type == Request::class) {
                    $arguments['request'] = $request;
                    continue;
                }

                if ($type == Params::class) {
                    $arguments['params'] = $params;
                    continue;
                }

                $param = $params->Get($parameter->getName());

                if (is_subclass_of($type, DatabaseObject::class)) {
                    $object = ($type)::ById($param);
                    $arguments[$parameter->getName()] = $object;
                    continue;
                }

                $arguments[$parameter->getName()] = $param;
                continue;
            }
            return $arguments;
        }
    }
}
