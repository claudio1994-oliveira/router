<?php



namespace Router\Test\Router;

use Router\Router\Router;
use PHPUnit\Framework\TestCase;
use Router\Contracts\MiddlewareInterface;
use Router\Tests\Middleware\LocalMiddleware;
use Router\Tests\Middleware\GlobalMiddleware;

class MiddlewareTest extends TestCase
{

    public function testMiddlewareHasTypeMiddlewareInterface()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $router = new Router();
        $middleware = new GlobalMiddleware();

        $router->addMiddleware($middleware);

        $middlewares = $router->getMiddlewares();


        foreach ($middlewares as $registeredMiddleware) {
            $this->assertInstanceOf(MiddlewareInterface::class, $registeredMiddleware);
        }
    }

    public function testMiddlewareWithoutImplementingMiddlewareInterface()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided object must implement MiddliwareInterface.');

        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new Router();

        $router->addMiddleware(new class
        {
        });
        $router->addRoute('/user', function () {
            return 'users';
        }, 'GET');

        $router->run();
    }
    public function testSetGlobalMiddleware()
    {
        $_SERVER['REQUEST_URI'] = '/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        session_start();

        $_SESSION['user'] = 'admin';

        $router = new Router();

        $router->addMiddleware(new GlobalMiddleware());

        $router->addRoute('/users', function () {
            return 'users';
        }, 'GET');

        $result = $router->run();

        $this->assertEquals('users', $result);
    }

    public function testFailedPassOfGloblaMiddleware()
    {
        $_SERVER['REQUEST_URI'] = '/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        session_unset();

        $router = new Router();

        $router->addMiddleware(new GlobalMiddleware());

        $router->addRoute('/users', function () {
            return 'users';
        }, 'GET');

        $result = $router->run();

        $this->assertEquals('not authorized', $result);
    }

    public function testSetLocalMiddleware()
    {
        $_SERVER['REQUEST_URI'] = '/users/local';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user'] = 'admin';
        $_SESSION['value'] = 'local';

        $router = new Router();

        $router->addMiddleware(new GlobalMiddleware());

        $router->addRoute('/users/local', function () {
            return 'local';
        }, 'GET', [new LocalMiddleware()]);


        $result = $router->run();


        $this->assertEquals('local', $result);
    }
}
