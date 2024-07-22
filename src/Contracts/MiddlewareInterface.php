<?php

namespace Router\Contracts;

use Router\Http\Request;
use Router\Http\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response;
}
