<?php

namespace Router\Tests\Middleware;

use Router\Contracts\MiddlewareInterface;
use Router\Http\Request;
use Router\Http\Response;

class GlobalMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if (!isset($_SESSION['user'])) {

            return 'not authorized';
        }

        return $next($request);
    }
}
