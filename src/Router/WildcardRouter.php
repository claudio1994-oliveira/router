<?php

namespace Router\Router;

class WildcardRouter
{
    private $parameters = [];

    public function resolveRoute($uri, &$routeCollection)
    {
        $keysRouteCollection = array_keys($routeCollection);
        $routeWithParamaters = [];

        foreach ($keysRouteCollection as $route) {
            if (preg_match('/{(\w+?)\}/', $route)) {
                $routeWithParamaters[] = $route;
            }
        }

        foreach ($routeWithParamaters as $route) {
            $routeWithParamater = preg_replace('/\/{(\w+?)\}/', '', $route);
            $uriWithParameter = preg_replace('/\/[0-9]+$/', '', $uri);

            if ($routeWithParamater === $uriWithParameter) {
                $routeCollection[$uri] = $routeCollection[$route];
                $this->parameters = $this->resolveParameters($uri);
            }
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    private function resolveParameters($uri)
    {
        $parameters = [];

        preg_match('/[0-9]+$/', $uri, $parameters);

        return $parameters;
    }
}
