<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LimitOffsetMiddleware
{
    private const DEFAULT_LIMIT = 20;

    public function handle(Request $request, Closure $next, int $maxLimit = 100)
    {
        $limit = $request->input('limit', self::DEFAULT_LIMIT);

        if ($limit > $maxLimit) {
            $request->merge([
                'limit' => $maxLimit
            ]);
        }

        return $next($request);
    }
}
