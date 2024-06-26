<?php

namespace Router\Router;

class Router
{
    private $uriServer;
    private $routeCollection = [];

    private $prefix = '';

    public function __construct()
    {
        $this->uriServer = $_SERVER['REQUEST_URI'];
    }
    public function addRoute($uri, $callback, $method = 'GET')
    {
        if (strtoupper($method) != $_SERVER['REQUEST_METHOD']) {
            throw new \Exception('Method not allowed');
        }

        $uri = ltrim($uri, '/');
        $prefix = $this->prefix ? '/' . ltrim($this->prefix, '/') : '';
        return $this->routeCollection[$prefix .  '/' . $uri] = $callback;
    }


    public function prefix($prefix, $routeGroup)
    {
        $this->prefix = $prefix;

        $routeGroup($this);
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

        return $this->controllerResolver($route, $wildcardRouter->getParameters());
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
}
