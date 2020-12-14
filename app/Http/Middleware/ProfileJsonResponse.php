<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProfileJsonResponse
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
        $response =  $next($request);

        if(!env('APP_DEBUG') ){
            return response;
        }

        if($response instanceof JsonResponse ){
            $response->setData(array_merge(
                $response->getData(true),[
                    'debugbar'=>Arr::only(app('debugbar')->getData(true),'queries')
                ]));
        }

        return $response;
    }
}
