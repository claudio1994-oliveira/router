<?php

namespace Router\Tests\Middleware;

use Router\Contracts\MiddlewareInterface;
use Router\Http\Request;


class LocalMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if ($_SESSION['value'] !== 'local') {

            return 'not authorized to access this route';
        }

        return $next($request);
    }
}
