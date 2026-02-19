<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateExternalApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key');
        $secret = $request->header('X-API-Secret');

        if (!$key || !$secret) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please verify your BixCash API credentials',
            ], 401);
        }

        $integration = ApiIntegration::where('api_key', $key)
            ->where('is_active', true)
            ->first();

        if (!$integration || !$integration->verifySecret($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please verify your BixCash API credentials',
            ], 401);
        }

        // IP whitelist check
        if (!empty($integration->allowed_ips) && !in_array($request->ip(), $integration->allowed_ips)) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please verify your BixCash API credentials',
            ], 401);
        }

        $request->attributes->set('api_integration', $integration);
        $request->attributes->set('partner_id', $integration->partner_id);

        $integration->recordUsage();

        return $next($request);
    }
}
