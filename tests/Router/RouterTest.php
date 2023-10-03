<?php

namespace Claud\Router\Test\Router;

use Claud\Router\Router\Router;
use InvalidArgumentException;
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

        $router->addRoute('/products', '\\Claud\\Router\\Tests\\Controller\\ProductController@index');

        $result = $router->run();

        $this->assertEquals('ProductController@index', $result);
    }

    public function testAWrongFormatToCallControllerAsASecondParameterOfTheOurRouter()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid call format');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', '\\Claud\\Router\\Tests\\Controller\\ProductController');

        $router->run();
    }

    public function testThrowExceptionWhenMethodDoesNotExistInAController()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method not found');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', '\\Claud\\Router\\Tests\\Controller\\ProductController@create');

        $router->run();
    }
}
