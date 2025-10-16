<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePartnerRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isPartner()) {
            abort(403, 'Access denied. Partner role required.');
        }

        // Check if partner profile exists
        $partnerProfile = $user->partnerProfile;

        if (!$partnerProfile) {
            abort(403, 'Partner profile not found.');
        }

        // Check if partner is approved
        if ($partnerProfile->status === 'pending') {
            return redirect()->route('partner.pending-approval');
        }

        if ($partnerProfile->status !== 'approved') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['phone' => 'Your partner account is not active. Please contact support.']);
        }

        return $next($request);
    }
}
