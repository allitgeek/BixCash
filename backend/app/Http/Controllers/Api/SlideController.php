<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $slides = Slide::where('is_active', true)
                ->scheduled()
                ->orderBy('order', 'asc')
                ->select([
                    'id', 'title', 'description', 'media_type', 'media_path',
                    'target_url', 'button_text', 'button_color', 'order'
                ])
                ->get()
                ->map(function ($slide) {
                    return [
                        'id' => $slide->id,
                        'title' => $slide->title,
                        'description' => $slide->description,
                        'media_type' => $slide->media_type,
                        'media_path' => $slide->media_path,
                        'target_url' => $slide->target_url,
                        'button_text' => $slide->button_text,
                        'button_color' => $slide->button_color ?: '#3498db',
                        'order' => $slide->order
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $slides,
                'count' => $slides->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load slides',
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
