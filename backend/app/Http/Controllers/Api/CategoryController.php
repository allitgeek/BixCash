<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::where('is_active', true)
                ->orderBy('order', 'asc')
                ->select([
                    'id', 'name', 'description', 'icon_path', 'color',
                    'order', 'meta_title', 'meta_description'
                ])
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'icon_path' => $category->icon_path,
                        'color' => $category->color ?: '#3498db',
                        'order' => $category->order,
                        'meta_title' => $category->meta_title,
                        'meta_description' => $category->meta_description,
                        'brands_count' => $category->brands()->where('is_active', true)->count()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load categories',
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
