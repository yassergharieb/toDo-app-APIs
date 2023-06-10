<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       


    if ($request->api_password !=  env('API_PASSWORD' , 'NHk7X50699O3S3a8V')  ) {

        // return $request->api_password;

        return response()->json('you are not authanticated' , 400);
        
       }
         
        return $next($request);
    }
}
