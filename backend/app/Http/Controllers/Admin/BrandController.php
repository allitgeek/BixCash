<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('website', 'LIKE', "%{$search}%");
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

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        $brands = $query->orderBy('order', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.brands.index', compact('brands', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.brands.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'logo_path' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'partner_id' => 'nullable|exists:users,id',
        ]);

        // Handle logo upload or URL
        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('brands', $logoName, 'public');
            $validated['logo_path'] = '/storage/' . $logoPath;
        } elseif (!$request->filled('logo_path')) {
            // If neither file nor URL provided, set to null
            $validated['logo_path'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['created_by'] = Auth::id();

        // Remove logo_file from validated data as it's not a database field
        unset($validated['logo_file']);

        $brand = Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $brand->load(['category', 'partner']);
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.brands.edit', compact('brand', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'logo_path' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'partner_id' => 'nullable|exists:users,id',
        ]);

        // Handle logo upload or URL
        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('brands', $logoName, 'public');
            $validated['logo_path'] = '/storage/' . $logoPath;
        } elseif (!$request->filled('logo_path')) {
            // If neither file nor URL provided, keep current logo
            unset($validated['logo_path']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Remove logo_file from validated data as it's not a database field
        unset($validated['logo_file']);

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully.');
    }

    /**
     * Toggle the active status of a brand.
     */
    public function toggleStatus(Brand $brand)
    {
        $brand->update([
            'is_active' => !$brand->is_active
        ]);

        $status = $brand->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Brand {$status} successfully.");
    }

    /**
     * Toggle the featured status of a brand.
     */
    public function toggleFeatured(Brand $brand)
    {
        $brand->update([
            'is_featured' => !$brand->is_featured
        ]);

        $status = $brand->is_featured ? 'marked as featured' : 'removed from featured';

        return redirect()->back()
            ->with('success', "Brand {$status} successfully.");
    }
}
