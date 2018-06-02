<?php

/*
Copyright @civaqhar (https://laracasts.com/discuss/channels/requests/laravel-5-cors-headers-with-filters/?page=3)
1. Put this file in App/Http/Middleware folder
2. Register App\Http\Middleware\CORSMiddleware
    Lumen: In bootstrap/app.php file, insert in this block:
        $app->middleware([
            ...
            App\Http\Middleware\CORSMiddleware::class,
        ]);
*/
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CORSMiddleware
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
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin, Authorization'
        ];

        if ($request->getMethod() == "OPTIONS") {
            $response = new Response();
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
