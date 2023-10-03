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
        return $this->routeCollection[$this->uriServer]();
    }
}
