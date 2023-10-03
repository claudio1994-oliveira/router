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
        if (!array_key_exists($this->uriServer, $this->routeCollection)) {
            throw new \Exception('No route found');
        }

        $route = $this->routeCollection[$this->uriServer];

        if (is_callable($route)) {
            return $this->routeCollection[$this->uriServer]();
        }

        return $this->controllerResolver($route);
    }

    private function controllerResolver($route)
    {
        $route = explode('@', $route);

        $controller = $route[0];
        $action = $route[1];

        $controller = 'Claud\\Router\\Tests\\Controller\\' . $controller;

        return call_user_func_array([new $controller, $action], []);
    }
}
