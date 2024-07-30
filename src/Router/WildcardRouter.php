<?php

namespace Router\Router;

class WildcardRouter
{
    private array $parameters = [];

    public function resolveRoute($uri, &$routeCollection, $method): void
    {
        $keysRouteCollection = array_keys($routeCollection);

        $routeWithParameters = [];

        foreach ($keysRouteCollection as $route) {
            if (preg_match('/{(\w+?)\}/', $route)) {
                $routeWithParameters[] = $route;
            }
        }

        foreach ($routeWithParameters as $route) {
            $routeWithParameter = preg_replace('/\/{(\w+?)\}/', '', $route);

            $uriWithParameter = preg_replace('/\/[0-9]+$/', '', $uri);

            if ($routeWithParameter === $uriWithParameter . '|' . $method) {
                $routeCollection[$uri . '|' . $method] = $routeCollection[$route];
                $this->parameters = $this->resolveParameters($uri);
            }
        }
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    private function resolveParameters($uri): array
    {
        $parameters = [];

        preg_match('/[0-9]+$/', $uri, $parameters);

        return $parameters;
    }
}
