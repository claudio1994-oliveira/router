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

    public function testValidateANoRouteFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No route found');

        $_SERVER['REQUEST_URI'] = '/user';

        $router = new Router();
        $router->run();
    }

    public function testRouteWithAControllerAssociated()
    {
        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', 'ProductController@index');

        $result = $router->run();

        $this->assertEquals('ProductController@index', $result);
    }
}
