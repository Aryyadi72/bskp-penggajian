<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class ValidateSalary
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->query('token');

            if (!$token || !JWTAuth::setToken($token)->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $payload = JWTAuth::setToken($token)->getPayload();
            $request->merge(['roles' => $payload['roles']]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        return $next($request);
    }
}
