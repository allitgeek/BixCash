<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ExplorePartner;
use App\Models\ExplorePartnerBranch;
use App\Models\ExplorePartnerMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExplorePartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = ExplorePartner::query()->with(['media', 'branches']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('category_tag', 'LIKE', "%{$s}%")
                  ->orWhere('short_description', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('type') && in_array($request->type, ['online', 'offline'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        $partners = $query->orderBy('type')
                          ->orderBy('display_order')
                          ->orderBy('id')
                          ->paginate(20)
                          ->withQueryString();

        return view('admin.explore-partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.explore-partners.create', [
            'partner' => new ExplorePartner(['type' => 'online', 'is_active' => true, 'display_order' => 0]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePartner($request);

        $partner = DB::transaction(function () use ($request, $validated) {
            $data = $validated;
            unset($data['logo_file']);
            $data['created_by'] = Auth::id();

            if ($request->hasFile('logo_file')) {
                $data['logo_path'] = $request->file('logo_file')->store('explore-partners', 'public');
            }

            $partner = ExplorePartner::create($data);
            $this->syncMedia($request, $partner);
            $this->syncBranches($request, $partner);

            return $partner;
        });

        ActivityLog::createLog(
            action: 'create',
            entityType: 'ExplorePartner',
            entityId: $partner->id,
            description: "Created explore partner: {$partner->name}",
        );

        return redirect()->route('admin.explore-partners.edit', $partner)
                         ->with('success', 'Partner created successfully.');
    }

    public function show(ExplorePartner $explore_partner)
    {
        $explore_partner->load(['media', 'branches', 'creator']);
        return view('admin.explore-partners.show', ['partner' => $explore_partner]);
    }

    public function edit(ExplorePartner $explore_partner)
    {
        $explore_partner->load(['media', 'branches']);
        return view('admin.explore-partners.edit', ['partner' => $explore_partner]);
    }

    public function update(Request $request, ExplorePartner $explore_partner)
    {
        $validated = $this->validatePartner($request, $explore_partner);

        DB::transaction(function () use ($request, $explore_partner, $validated) {
            $data = $validated;
            unset($data['logo_file']);

            if ($request->hasFile('logo_file')) {
                if ($explore_partner->logo_path) {
                    Storage::disk('public')->delete($explore_partner->logo_path);
                }
                $data['logo_path'] = $request->file('logo_file')->store('explore-partners', 'public');
            } elseif ($request->boolean('remove_logo')) {
                if ($explore_partner->logo_path) {
                    Storage::disk('public')->delete($explore_partner->logo_path);
                }
                $data['logo_path'] = null;
            }

            $explore_partner->update($data);
            $this->syncMedia($request, $explore_partner);
            $this->syncBranches($request, $explore_partner);
        });

        ActivityLog::createLog(
            action: 'update',
            entityType: 'ExplorePartner',
            entityId: $explore_partner->id,
            description: "Updated explore partner: {$explore_partner->name}",
        );

        return redirect()->route('admin.explore-partners.edit', $explore_partner)
                         ->with('success', 'Partner updated successfully.');
    }

    public function destroy(ExplorePartner $explore_partner)
    {
        // Clean up uploaded files
        if ($explore_partner->logo_path) {
            Storage::disk('public')->delete($explore_partner->logo_path);
        }
        foreach ($explore_partner->media as $media) {
            if ($media->image_path) {
                Storage::disk('public')->delete($media->image_path);
            }
        }

        $name = $explore_partner->name;
        $id = $explore_partner->id;
        $explore_partner->delete();

        ActivityLog::createLog(
            action: 'delete',
            entityType: 'ExplorePartner',
            entityId: $id,
            description: "Deleted explore partner: {$name}",
        );

        return redirect()->route('admin.explore-partners.index')
                         ->with('success', 'Partner deleted.');
    }

    public function toggleStatus(ExplorePartner $explore_partner)
    {
        $explore_partner->is_active = ! $explore_partner->is_active;
        $explore_partner->save();

        return response()->json([
            'success' => true,
            'is_active' => $explore_partner->is_active,
        ]);
    }

    public function toggleFeatured(ExplorePartner $explore_partner)
    {
        $explore_partner->is_featured = ! $explore_partner->is_featured;
        $explore_partner->save();

        return response()->json([
            'success' => true,
            'is_featured' => $explore_partner->is_featured,
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:explore_partners,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            ExplorePartner::where('id', $id)->update(['display_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    // ---------- internals ----------

    protected function validatePartner(Request $request, ?ExplorePartner $partner = null): array
    {
        $rules = [
            'name' => 'required|string|max:150',
            'type' => 'required|in:online,offline',
            'category_tag' => 'nullable|string|max:80',
            'short_description' => 'nullable|string|max:2000',
            'visit_url' => 'nullable|url|max:500',
            'branch_count_label' => 'nullable|string|max:80',
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
            // media + branches validated piecewise in sync*
        ];
        $validated = $request->validate($rules);

        // Normalize booleans (checkboxes that weren't ticked don't appear in the request)
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);

        return $validated;
    }

    /**
     * Sync media rows against the submitted media[] array.
     *
     * Each row may carry:
     *   id (if editing an existing row), kind ('image'|'video'),
     *   image_file (upload), keep_image (1 = reuse current image_path),
     *   video_url, caption, display_order, is_active
     */
    protected function syncMedia(Request $request, ExplorePartner $partner): void
    {
        $rows = $request->input('media', []);
        if (! is_array($rows)) {
            $rows = [];
        }
        // Hard cap 5
        $rows = array_slice(array_values($rows), 0, 5);

        $existingIds = $partner->media()->pluck('id')->all();
        $submittedIds = [];

        foreach ($rows as $i => $row) {
            $kind = in_array($row['kind'] ?? null, ['image', 'video'], true) ? $row['kind'] : 'image';
            $caption = isset($row['caption']) ? mb_substr((string) $row['caption'], 0, 160) : null;
            $order = isset($row['display_order']) ? (int) $row['display_order'] : $i;
            $active = (bool) ($row['is_active'] ?? true);

            $payload = [
                'explore_partner_id' => $partner->id,
                'kind' => $kind,
                'caption' => $caption,
                'display_order' => $order,
                'is_active' => $active,
                'video_url' => $kind === 'video' ? (trim((string) ($row['video_url'] ?? '')) ?: null) : null,
            ];

            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $existing = $id ? $partner->media->firstWhere('id', $id) : null;

            // Handle image file — only for kind=image
            if ($kind === 'image') {
                $file = $request->file("media.$i.image_file");
                if ($file) {
                    // Replace image — delete old if any
                    if ($existing?->image_path) {
                        Storage::disk('public')->delete($existing->image_path);
                    }
                    $payload['image_path'] = $file->store('explore-partners/media', 'public');
                } elseif ($existing) {
                    // Keep existing image path
                    $payload['image_path'] = $existing->image_path;
                } else {
                    $payload['image_path'] = null;
                }
            } else {
                // Switched to video — wipe any old image file
                if ($existing?->image_path) {
                    Storage::disk('public')->delete($existing->image_path);
                }
                $payload['image_path'] = null;
            }

            if ($existing) {
                $existing->update($payload);
                $submittedIds[] = $existing->id;
            } else {
                $created = ExplorePartnerMedia::create($payload);
                $submittedIds[] = $created->id;
            }
        }

        // Delete rows that were removed in the UI
        $toDelete = array_diff($existingIds, $submittedIds);
        if (! empty($toDelete)) {
            $deleting = $partner->media()->whereIn('id', $toDelete)->get();
            foreach ($deleting as $m) {
                if ($m->image_path) {
                    Storage::disk('public')->delete($m->image_path);
                }
            }
            $partner->media()->whereIn('id', $toDelete)->delete();
        }
    }

    protected function syncBranches(Request $request, ExplorePartner $partner): void
    {
        // Online partners shouldn't carry branches — wipe them if the type is online.
        if ($partner->type === 'online') {
            foreach ($partner->branches as $b) {
                $b->delete();
            }
            return;
        }

        $rows = $request->input('branches', []);
        if (! is_array($rows)) {
            $rows = [];
        }

        $existingIds = $partner->branches()->pluck('id')->all();
        $submittedIds = [];

        foreach (array_values($rows) as $i => $row) {
            if (empty($row['name']) || empty($row['address']) || empty($row['city'])) {
                // Skip empty rows (user may have added a blank placeholder)
                continue;
            }

            // Build weekly_schedule payload from per-day fields
            $schedule = [];
            foreach (ExplorePartnerBranch::DAYS as $day) {
                $dayRow = $row['weekly_schedule'][$day] ?? [];
                $schedule[$day] = [
                    'closed' => ! empty($dayRow['closed']),
                    'open' => isset($dayRow['open']) ? (string) $dayRow['open'] : null,
                    'close' => isset($dayRow['close']) ? (string) $dayRow['close'] : null,
                ];
            }

            $payload = [
                'explore_partner_id' => $partner->id,
                'name' => mb_substr((string) $row['name'], 0, 150),
                'address' => mb_substr((string) $row['address'], 0, 255),
                'city' => mb_substr((string) $row['city'], 0, 80),
                'phone' => isset($row['phone']) ? mb_substr((string) $row['phone'], 0, 40) : null,
                'lat' => $row['lat'] !== '' && $row['lat'] !== null ? (float) $row['lat'] : null,
                'lng' => $row['lng'] !== '' && $row['lng'] !== null ? (float) $row['lng'] : null,
                'directions_url' => isset($row['directions_url']) && trim($row['directions_url']) !== ''
                    ? mb_substr(trim((string) $row['directions_url']), 0, 500)
                    : null,
                'weekly_schedule' => $schedule,
                'display_order' => isset($row['display_order']) ? (int) $row['display_order'] : $i,
                'is_active' => (bool) ($row['is_active'] ?? true),
            ];

            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $existing = $id ? $partner->branches->firstWhere('id', $id) : null;

            if ($existing) {
                $existing->update($payload);
                $submittedIds[] = $existing->id;
            } else {
                $created = ExplorePartnerBranch::create($payload);
                $submittedIds[] = $created->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (! empty($toDelete)) {
            $partner->branches()->whereIn('id', $toDelete)->delete();
        }
    }
}
