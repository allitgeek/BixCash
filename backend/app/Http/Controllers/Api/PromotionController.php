<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\JsonResponse;

class PromotionController extends Controller
{
    /**
     * Get all active promotions for the frontend.
     */
    public function index(): JsonResponse
    {
        try {
            $promotions = Promotion::active()
                ->ordered()
                ->get()
                ->map(function ($promotion) {
                    return [
                        'id' => $promotion->id,
                        'brand_name' => $promotion->brand_name,
                        'logo_url' => $promotion->logo_url,
                        'discount_type' => $promotion->discount_type,
                        'discount_value' => $promotion->discount_value,
                        'discount_text' => $promotion->formatted_discount,
                        'order' => $promotion->order,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $promotions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching promotions',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
