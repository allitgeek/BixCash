<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand_name', 'LIKE', "%{$search}%")
                  ->orWhere('discount_text', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by discount type
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }

        $promotions = $query->ordered()->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promotions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'logo_path' => 'nullable|string|max:500',
            'discount_type' => 'required|in:upto,flat,coming_soon',
            'discount_value' => 'required_unless:discount_type,coming_soon|nullable|integer|min:1|max:100',
            'discount_text' => 'nullable|string|max:40',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle logo upload or URL
        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('promotions', $logoName, 'public');
            $validated['logo_path'] = '/storage/' . $logoPath;
        } elseif (!$request->filled('logo_path')) {
            // If neither file nor URL provided, set to null
            $validated['logo_path'] = null;
        }

        // For coming_soon, set discount_value to null if not provided
        if ($request->discount_type === 'coming_soon' && empty($validated['discount_value'])) {
            $validated['discount_value'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['created_by'] = Auth::id();

        // Remove logo_file from validated data as it's not a database field
        unset($validated['logo_file']);

        $promotion = Promotion::create($validated);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        $promotion->load('creator');
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'logo_path' => 'nullable|string|max:500',
            'discount_type' => 'required|in:upto,flat,coming_soon',
            'discount_value' => 'required_unless:discount_type,coming_soon|nullable|integer|min:1|max:100',
            'discount_text' => 'nullable|string|max:40',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle logo upload or URL
        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('promotions', $logoName, 'public');
            $validated['logo_path'] = '/storage/' . $logoPath;
        } elseif (!$request->filled('logo_path')) {
            // If neither file nor URL provided, keep current logo
            unset($validated['logo_path']);
        }

        // For coming_soon, set discount_value to null if not provided
        if ($request->discount_type === 'coming_soon' && empty($validated['discount_value'])) {
            $validated['discount_value'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        // Remove logo_file from validated data as it's not a database field
        unset($validated['logo_file']);

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }

    /**
     * Toggle the active status of a promotion.
     */
    public function toggleStatus(Promotion $promotion)
    {
        $promotion->update([
            'is_active' => !$promotion->is_active
        ]);

        $status = $promotion->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Promotion {$status} successfully.");
    }

    /**
     * Reorder promotions via AJAX drag-and-drop.
     */
    public function reorder(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'promotions' => 'required|array',
                'promotions.*.id' => 'required|integer|exists:promotions,id',
                'promotions.*.order' => 'required|integer|min:1'
            ]);

            // Update each promotion's order
            foreach ($validated['promotions'] as $promotionData) {
                Promotion::where('id', $promotionData['id'])
                    ->update(['order' => $promotionData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Promotion order updated successfully!'
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
                'message' => 'An error occurred while updating promotion order: ' . $e->getMessage()
            ], 500);
        }
    }
}
