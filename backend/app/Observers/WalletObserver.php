<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;

class WalletObserver
{
    /**
     * Handle the User "created" event.
     * Automatically create a wallet for new users (customers & partners)
     */
    public function created(User $user): void
    {
        // Only create wallets for customers and partners
        if ($user->isCustomer() || $user->isPartner()) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]);
        }
    }
}
