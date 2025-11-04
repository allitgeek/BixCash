<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has customer role
        if (!$user->isCustomer()) {
            // Redirect partners to their dashboard
            if ($user->isPartner()) {
                $partnerProfile = $user->partnerProfile;

                if ($partnerProfile && $partnerProfile->status === 'pending') {
                    return redirect()->route('partner.pending-approval');
                } elseif ($partnerProfile && $partnerProfile->status === 'approved') {
                    return redirect()->route('partner.dashboard');
                }
            }

            // Redirect admins to their dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // For any other role, deny access
            abort(403, 'Access denied. Customer role required.');
        }

        return $next($request);
    }
}
