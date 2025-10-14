<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityLogService;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (SecurityLogService::isIpBlocked($ip)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many suspicious activities. Access temporarily blocked.'
            ], 429);
        }

        return $next($request);
    }
}
