<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Sample dashboard data (will be replaced with real data later)
        $dashboardData = [
            'user' => [
                'name' => 'John Doe',
                'rating' => 5,
                'avatar' => '/images/avatars/default.png'
            ],
            'stats' => [
                'totalEarnings' => 2543.50,
                'monthlyEarnings' => 345.20,
                'totalTransactions' => 127,
                'pendingRewards' => 89.75
            ],
            'recentActivities' => [
                ['type' => 'purchase', 'description' => 'Purchase at Khaadi', 'amount' => 45.50, 'date' => '2024-01-15'],
                ['type' => 'reward', 'description' => 'Cashback earned', 'amount' => 12.30, 'date' => '2024-01-14'],
                ['type' => 'withdrawal', 'description' => 'Withdrew to bank', 'amount' => -100.00, 'date' => '2024-01-13']
            ],
            'chartData' => [
                'earnings' => [120, 145, 167, 189, 201, 234, 267, 289, 310, 334, 345, 367],
                'transactions' => [12, 15, 18, 22, 19, 25, 28, 24, 31, 29, 33, 35]
            ]
        ];

        return view('dashboard', compact('dashboardData'));
    }
}