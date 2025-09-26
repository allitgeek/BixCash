<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index(Request $request)
    {
        $query = Slide::with('creator');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // Order by order and created date
        $slides = $query->orderBy('order', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'media_path' => 'required|string|max:500',
            'target_url' => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_color' => 'nullable|string|max:7',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');

        $slide = Slide::create($validated);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide created successfully.');
    }

    public function show(Slide $slide)
    {
        $slide->load('creator');
        return view('admin.slides.show', compact('slide'));
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'media_path' => 'required|string|max:500',
            'target_url' => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_color' => 'nullable|string|max:7',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $slide->update($validated);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide updated successfully.');
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();

        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide deleted successfully.');
    }

    public function toggleStatus(Slide $slide)
    {
        $slide->update([
            'is_active' => !$slide->is_active
        ]);

        $status = $slide->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Slide {$status} successfully.");
    }
}
