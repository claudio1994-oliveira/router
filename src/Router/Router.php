<?php

namespace Claud\Router\Router;

class Router
{
    private $uriServer;
    private $routeCollection = [];

    public function __construct()
    {
        $this->uriServer = $_SERVER['REQUEST_URI'];
    }
    public function addRoute($uri, $callback)
    {
        return $this->routeCollection[$uri] = $callback;
    }

    public function run()
    {
        $wildcardRouter = new WildcardRouter();
        $wildcardRouter->resolveRoute($this->uriServer, $this->routeCollection);


        if (!array_key_exists($this->uriServer, $this->routeCollection)) {
            throw new \Exception('No route found');
        }


        $route = $this->routeCollection[$this->uriServer];

        if (is_callable($route)) {
            $parameters = $wildcardRouter->getParameters();

            if (!empty($parameters)) {
                return $route($parameters[0]);
            }

            return $route();
        }

        return $this->controllerResolver($route);
    }

    private function controllerResolver($route, $parameters = [])
    {
        if (!strpos($route, '@')) {
            throw new \InvalidArgumentException('Invalid call format');
        }
        $route = explode('@', $route);

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
}
