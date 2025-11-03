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
        // Determine validation rules based on whether file or URL is provided
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'target_url' => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_color' => 'nullable|string|max:7',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ];

        // Add validation for either file or URL
        if ($request->hasFile('media_file')) {
            $rules['media_file'] = 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov,wmv|max:204800'; // 200MB max
        } else {
            $rules['media_path'] = 'required|url|max:500';
        }

        $validated = $request->validate($rules);

        // Handle file upload or URL
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('slides', $filename, 'public');
            $validated['media_path'] = '/storage/' . $path;
        }

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
        // Determine validation rules for update
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'target_url' => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_color' => 'nullable|string|max:7',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ];

        // Add validation for file upload or URL (both optional for update)
        if ($request->hasFile('media_file')) {
            $rules['media_file'] = 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov,wmv|max:204800'; // 200MB max
        } elseif ($request->filled('media_path')) {
            $rules['media_path'] = 'required|url|max:500';
        }

        $validated = $request->validate($rules);

        // Handle file upload if provided
        if ($request->hasFile('media_file')) {
            // Delete old file if it exists and is not a URL
            if ($slide->media_path && !filter_var($slide->media_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $slide->media_path));
            }

            $file = $request->file('media_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('slides', $filename, 'public');
            $validated['media_path'] = '/storage/' . $path;
        } elseif (!$request->filled('media_path') && !$request->hasFile('media_file')) {
            // Keep existing media_path if neither file nor URL provided
            unset($validated['media_path']);
        }

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

    public function reorder(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'slides' => 'required|array',
                'slides.*.id' => 'required|integer|exists:slides,id',
                'slides.*.order' => 'required|integer|min:1'
            ]);

            // Update each slide's order
            foreach ($validated['slides'] as $slideData) {
                Slide::where('id', $slideData['id'])
                    ->update(['order' => $slideData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slide order updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating slide order: ' . $e->getMessage()
            ], 500);
        }
    }
}
