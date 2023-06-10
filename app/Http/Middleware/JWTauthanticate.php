<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTauthanticate extends BaseMiddleware
{


    public function handle(Request $request, Closure $next, $guard = null)
    {

        if ($guard != null) {
            auth()->shouldUse($guard);
            $token  =  $request->header('api-token');
            $request->headers->set('api-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer ' . $token, true);


            try {

                $user =  JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException $ex) {

                return response()->json(['msg' =>  $ex->getMessage(), 'code' => $ex->getCode()]);
            } catch (JWTException $ex) {
                return response()->json(['msg' =>  $ex->getMessage(), 'code' => $ex->getCode()]);
            }
        }

        return $next($request);
    }
}
