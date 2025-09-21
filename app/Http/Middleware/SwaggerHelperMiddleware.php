<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class SwaggerHelperMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response) $next
     * @throws JsonException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $req = $request->all();
        if (isset($req['filter']['a_search']) && is_array($req['filter']['a_search'])) {
            $formatedData = [];
            foreach ($req['filter']['a_search'] as $ASearch) {
                if (is_string($ASearch)) {
                    $formatedData[] = json_decode($ASearch, true, 512, JSON_THROW_ON_ERROR);
                }
            }
            $req['filter']['a_search'] = $formatedData;
            $request->merge($req);
        }

        return $next($request);
    }
}
