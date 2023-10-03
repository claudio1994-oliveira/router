<?php

namespace Claud\Router\Test\Router;

use Claud\Router\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testRouterSetRoutes()
    {
        $_SERVER['REQUEST_URI'] = '/users';

        $router = new Router();

        $router->addRoute('/users', function () {
            return 'users';
        });

        $result = $router->run();

        $this->assertEquals('users', $result);
    }
}
