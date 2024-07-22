<?php

namespace Router\Router;

use Router\Http\Request;
use Router\Contracts\MiddlewareInterface;

class Router
{
    private $uriServer;
    private $routeCollection = [];
    private $globalMiddlewares = [];

    private $prefix = '';

    public function __construct()
    {
        $this->uriServer = $_SERVER['REQUEST_URI'];
    }
    public function addRoute($uri, $callback, $method = 'GET', $middlewares = [])
    {

        $method = strtoupper($method);

        if ($method == 'GET' || $method == 'POST') {

            $middlewareList = [];

            foreach ($middlewares as $middleware) {

                $middlewareList[] = $middleware;
            }


            $uri = ltrim($uri, '/');
            $prefix = $this->prefix ? '/' . ltrim($this->prefix, '/') : '';

            return $this->routeCollection[$prefix .  '/' . $uri] = ['callback' => $callback, 'method' => $method, 'middlewares' => $middlewareList];
        }


        throw new \Exception('Method not allowed');
    }


    public function prefix($prefix, $routeGroup)
    {
        $this->prefix = $prefix;

        $routeGroup($this);
    }

    public function addMiddleware($middleware)
    {
        $this->globalMiddlewares[] = $middleware;
    }

    public function run()
    {
        $wildcardRouter = new WildcardRouter();
        $wildcardRouter->resolveRoute($this->uriServer, $this->routeCollection);


        if (!array_key_exists($this->uriServer, $this->routeCollection)) {

            throw new \Exception('No route found');
        }

        $route = $this->routeCollection[$this->uriServer];

        $middlewares = array_merge($this->globalMiddlewares, $route['middlewares']);

        return $this->handleMiddleware(
            new Request(
                $_SERVER['REQUEST_METHOD'],
                $this->uriServer,
                getallheaders(),
                file_get_contents('php://input'),
                $_POST,
                $_GET,
                $_SERVER,
                $_FILES
            ),
            $middlewares,
            function ($request) use ($route, $wildcardRouter) {
                if (is_callable($route['callback'])) {
                    $parameters = $wildcardRouter->getParameters();
                    return call_user_func_array($route['callback'], $parameters);
                }
                return $this->controllerResolver($route['callback'], $wildcardRouter->getParameters());
            }
        );
    }

    private function handleMiddleware(Request $request, $middlewares, callable $callback)
    {
        $middleware = array_shift($middlewares);

        if ($middleware) {
            $middlewareInstance = new $middleware();

            if ($middlewareInstance instanceof MiddlewareInterface) {

                return $middlewareInstance
                    ->handle($request, function ($request) use ($middlewares, $callback) {
                        return $this->handleMiddleware($request, $middlewares, $callback);
                    });
            } else {
                throw new \InvalidArgumentException('The provided object must implement MiddliwareInterface.');
            }
        }

        return call_user_func($callback, $request);
    }

    private function controllerResolver($route, $parameters = [])
    {

        if (!is_array($route)) {
            throw new \InvalidArgumentException('Invalid call format');
        }

        $controller = $route[0];

        $action = $route[1];

        if (!class_exists($controller)) {
            throw new \InvalidArgumentException('Class not found');
        }


        if (!method_exists(new $controller, $action)) {
            throw new \BadMethodCallException('Method not found');
        }

        return call_user_func_array([new $controller, $action], $parameters);
    }

    public function getMiddlewares()
    {
        return $this->globalMiddlewares;
    }
}
