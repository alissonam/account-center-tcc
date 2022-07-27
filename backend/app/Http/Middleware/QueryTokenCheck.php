<?php

namespace App\Http\Middleware;

use App\Http\Services\Service;
use Closure;
use Illuminate\Http\Response;

class QueryTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $queryToken = $request->query('token');

        if (!$queryToken)
        {
            throw Service::exception(['message' => 'Não autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $envQueryToken = env('EXTERNAL_QUERY_TOKEN');

        if ($queryToken !== $envQueryToken)
        {
            throw Service::exception(['message' => 'Não autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
