<?php

namespace App\Http\Controllers;

use App\Models\ExplorePartner;

class HomeController extends Controller
{
    public function index()
    {
        $onlinePartners = ExplorePartner::active()
            ->online()
            ->ordered()
            ->with(['media' => fn($q) => $q->where('is_active', true)->orderBy('display_order')])
            ->take(6)
            ->get();

        $offlinePartners = ExplorePartner::active()
            ->offline()
            ->ordered()
            ->with([
                'media' => fn($q) => $q->where('is_active', true)->orderBy('display_order'),
                'branches' => fn($q) => $q->where('is_active', true)->orderBy('display_order'),
            ])
            ->take(6)
            ->get();

        $onlineTotal = ExplorePartner::active()->online()->count();
        $offlineTotal = ExplorePartner::active()->offline()->count();

        // Branch data for the drawer — keyed by partner slug, pre-built so the
        // frontend JS has no extra lookups.
        $branchesByPartner = $offlinePartners->mapWithKeys(function ($p) {
            return [
                $p->slug => $p->branches->map(function ($b) {
                    return [
                        'name'          => $b->name,
                        'address'       => $b->address,
                        'city'          => $b->city,
                        'phone'         => $b->phone,
                        'hours'         => $b->todayHoursLabel() ?? 'Closed today',
                        'status'        => $b->currentStatus(),
                        'directions'    => $b->resolvedDirectionsUrl(),
                    ];
                })->values()->all(),
            ];
        })->all();

        return view('welcome', compact(
            'onlinePartners', 'offlinePartners',
            'onlineTotal', 'offlineTotal',
            'branchesByPartner'
        ));
    }
}
