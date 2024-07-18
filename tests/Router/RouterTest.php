<?php

namespace Router\Test\Router;

use Router\Router\Router;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    public function testRouterSetRoutes()
    {
        $_SERVER['REQUEST_URI'] = '/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new Router();

        $router->addRoute('/users', function () {
            return 'users';
        }, 'GET');

        $result = $router->run();

        $this->assertEquals('users', $result);
    }

    public function testValidateHTTPMethod()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Method not allowed');

        $_SERVER['REQUEST_URI'] = '/user';


        $router = new Router();
        $router->addRoute('/user', function () {
            return 'users';
        }, 'delete');

        $router->run();
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

        $router->addRoute('/products', ['\\Router\\Tests\\Controller\\ProductController', 'index']);

        $result = $router->run();

        $this->assertEquals('ProductController@index', $result);
    }

    public function testAWrongFormatToCallControllerAsASecondParameterOfTheOurRouter()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid call format');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', '\\Router\\Tests\\Controller\\ProductController');

        $router->run();
    }

    public function testThrowExceptionWhenClassDoesNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class not found');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', ['\\Router\\Tests\\Controller\\UserController', 'create']);

        $router->run();
    }

    public function testThrowExceptionWhenMethodDoesNotExistInAController()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method not found');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', ['\\Router\\Tests\\Controller\\ProductController', 'create']);

        $router->run();
    }

    public function testCallableRouterWithDynamicParameters()
    {
        $_SERVER['REQUEST_URI'] = '/products/1';

        $router = new Router();

        $router->addRoute('/products/{id}', function ($id) {
            return "Rota com parâmetro dinâmico {$id}";
        });

        $result = $router->run();

        $this->assertEquals('Rota com parâmetro dinâmico 1', $result);
    }

    public function testStringRouterWithDynamicParameters()
    {
        $_SERVER['REQUEST_URI'] = '/products/1';

        $router = new Router();

        $router->addRoute('/products/{id}', ['\\Router\\Tests\\Controller\\ProductController', 'show']);

        $result = $router->run();

        $this->assertEquals('Rota com parâmetro dinâmico 1', $result);
    }

    public function testRouterWithPrefix()
    {
        $_SERVER['REQUEST_URI'] = '/users/edit/1';

        $router = new Router();

        $router->prefix('/users', function (Router $router) {
            $router->addRoute('/edit/{id}', function ($id) {
                return "Rota com prefixo e parâmetro dinâmico {$id}";
            });

            $router->addRoute('/update/{id}', function ($id) {
                return "Rota com prefixo e parâmetro dinâmico {$id}";
            });
        });

        $result = $router->run();

        $this->assertEquals('Rota com prefixo e parâmetro dinâmico 1', $result);
    }
}
