<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartnerTransaction;
use App\Models\PurchaseHistory;
use App\Models\User;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExternalApiController extends Controller
{
    public function verifyMember(Request $request): JsonResponse
    {
        $request->validate([
            'membership_number' => 'required|string|digits:10',
        ]);

        $customer = $this->findCustomer($request->membership_number);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Member verified',
            'data' => [
                'member_name' => $this->maskName($customer->name),
                'is_active' => (bool) $customer->is_active,
            ],
        ]);
    }

    public function reportTransaction(Request $request): JsonResponse
    {
        $request->validate([
            'membership_number' => 'required|string',
            'order_id' => 'required|string|max:100',
            'order_amount' => 'required|numeric|min:1',
            'order_date' => 'required|date',
            'delivered_at' => 'required|date',
        ]);

        // Idempotency check
        $existing = PartnerTransaction::where('external_order_id', $request->order_id)
            ->where('source', 'api')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction already recorded',
                'data' => [
                    'transaction_code' => $existing->transaction_code,
                    'recorded_amount' => (float) $existing->invoice_amount,
                ],
            ]);
        }

        $customer = $this->findCustomer($request->membership_number);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found',
            ], 404);
        }

        $partnerId = $request->attributes->get('partner_id');
        $brand = Brand::where('partner_id', $partnerId)->first();

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Partner brand not configured',
            ], 422);
        }

        $transaction = PartnerTransaction::create([
            'partner_id' => $partnerId,
            'customer_id' => $customer->id,
            'brand_id' => $brand->id,
            'invoice_amount' => $request->order_amount,
            'transaction_date' => $request->order_date,
            'status' => 'confirmed',
            'confirmed_at' => $request->delivered_at,
            'confirmed_by_customer' => false,
            'source' => 'api',
            'external_order_id' => $request->order_id,
        ]);

        PurchaseHistory::create([
            'user_id' => $customer->id,
            'brand_id' => $brand->id,
            'partner_transaction_id' => $transaction->id,
            'order_id' => $transaction->transaction_code,
            'amount' => $request->order_amount,
            'status' => 'confirmed',
            'confirmed_by_customer' => false,
            'confirmation_method' => 'auto',
            'description' => $brand->name . ' order ' . $request->order_id,
            'purchase_date' => $request->delivered_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction recorded',
            'data' => [
                'transaction_code' => $transaction->transaction_code,
                'recorded_amount' => (float) $transaction->invoice_amount,
            ],
        ]);
    }

    private function findCustomer(string $membershipNumber): ?User
    {
        return User::where('phone', 'LIKE', '%' . $membershipNumber)
            ->whereHas('role', fn($q) => $q->where('name', 'customer'))
            ->where('is_active', true)
            ->first();
    }

    private function maskName(string $name): string
    {
        $parts = explode(' ', trim($name));

        if (count($parts) <= 1) {
            return $parts[0];
        }

        return $parts[0] . ' ' . mb_substr(end($parts), 0, 1) . '.';
    }
}
