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
            // $token = $request->bearerToken();
            $token = $request->query('token');

            // dd($token);

        // Debug token
        Log::info('Received Token: ' . $token);

            // Verifikasi token
            if (!$token || !JWTAuth::setToken($token)->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Ambil payload dari token
            $payload = JWTAuth::setToken($token)->getPayload();
            $roles = $payload['roles']; // Ambil roles

            // Cek apakah user adalah admin
            if (!in_array('Admin', $roles)) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        return $next($request);
    }
}
