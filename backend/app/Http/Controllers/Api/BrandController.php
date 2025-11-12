<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Brand::where('is_active', true)
                ->with('category:id,name')
                ->select([
                    'id', 'name', 'description', 'logo_path', 'website',
                    'commission_rate', 'category_id', 'order', 'is_featured'
                ]);

            // Filter by category if requested
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter featured brands if requested
            if ($request->boolean('featured')) {
                $query->where('is_featured', true);
            }

            $brands = $query->orderBy('order', 'asc')
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'description' => $brand->description,
                        'logo_path' => $brand->logo_path,
                        'website_url' => $brand->website,
                        'commission_rate' => $brand->commission_rate,
                        'category' => $brand->category ? [
                            'id' => $brand->category->id,
                            'name' => $brand->category->name
                        ] : null,
                        'order' => $brand->order,
                        'is_featured' => $brand->is_featured
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $brands,
                'count' => $brands->count(),
                'featured_count' => $brands->where('is_featured', true)->count()
            ])->header('Cache-Control', 'public, max-age=600'); // Cache for 10 minutes
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load brands',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
