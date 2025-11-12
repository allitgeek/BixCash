<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $table = 'purchase_history';

    protected $fillable = [
        'user_id',
        'brand_id',
        'partner_transaction_id',
        'order_id',
        'amount',
        'cashback_amount',
        'cashback_percentage',
        'status',
        'confirmed_by_customer',
        'confirmation_method',
        'description',
        'purchase_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cashback_amount' => 'decimal:2',
        'cashback_percentage' => 'decimal:2',
        'purchase_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function partnerTransaction()
    {
        return $this->belongsTo(PartnerTransaction::class, 'partner_transaction_id');
    }
}
