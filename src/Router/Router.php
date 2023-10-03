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
        return $this->routeCollection[$this->uriServer]();
    }
}
