<?php

namespace Server\Routing {

    use Closure;
    use ReflectionFunction;
    use Server\View\View;
    use Server\Request\Params;
    use Server\Request\Request;
    use Server\Request\RequestUri;
    use Server\Response\Response;

    class Route {

        private static array $routes = [];

        public static function Get(string $uri, callable $callback): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('GET', $requestUri, $callback);
        }

        public static function Post(string $uri, callable $callback): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('POST', $requestUri, $callback);
        }

        public static function Put(string $uri, callable $callback): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('PUT', $requestUri, $callback);
        }

        public static function Delete(string $uri, callable $callback): void {
            $requestUri = new RequestUri($uri);
            static::$routes[] = new Route('DELETE', $requestUri, $callback);
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

        public function __construct(string $method, RequestUri $requestUri, callable $callback) {
            $this->method = $method;
            $this->requestUri = $requestUri;
            $this->callback = $callback;
        }

        private string $method = 'GET';
        private ?RequestUri $requestUri = null;
        private ?Closure $callback = null;

        public function VerifyMethod(string $method): bool {
            return $this->method == $method;
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
            $arguments = [];

            $reflectionFunction = new ReflectionFunction($this->callback);
            foreach ($reflectionFunction->getParameters() as $parameter) {
                if ($parameter->getName() == 'request') $arguments['request'] = $request;
                if ($parameter->getName() == 'response') $arguments['response'] = Response::Instance();
                if ($parameter->getName() == 'view') $arguments['view'] = View::Instance();
                if ($parameter->getName() == 'params') $arguments['params'] = new Params($this->requestUri, $request->RequestUri());
            }

            return call_user_func_array($this->callback, $arguments);
        }
    }
}
