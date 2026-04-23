{{--
  Explore Partners — single-page form (create + edit).
  Driven by Alpine.js. All nested collections (media, branches, weekly schedule)
  post as indexed arrays: media[i][field], branches[i][field], branches[i][weekly_schedule][mon][open], etc.
--}}

@php
    $isEdit = $partner->exists;
    // Seed Alpine state from the model on edit; blank shapes on create.
    $initialMedia = $isEdit
        ? $partner->media->map(fn($m) => [
            '_key'          => 'm'.$m->id,
            'id'            => $m->id,
            'kind'          => $m->kind,
            'image_url'     => $m->image_path ? asset('storage/'.$m->image_path) : null,
            'image_path'    => $m->image_path,
            'video_url'     => $m->video_url ?? '',
            'caption'       => $m->caption ?? '',
            'display_order' => $m->display_order,
            'is_active'     => (bool) $m->is_active,
          ])->values()->all()
        : [];

    $dayKeys = ['mon','tue','wed','thu','fri','sat','sun'];
    $dayLabels = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
    $defaultSchedule = collect($dayKeys)->mapWithKeys(fn($d) => [$d => ['closed'=>false,'open'=>'10:00','close'=>'22:00']])->all();

    $initialBranches = $isEdit
        ? $partner->branches->map(function($b) use ($dayKeys, $defaultSchedule) {
            $sched = is_array($b->weekly_schedule) ? $b->weekly_schedule : $defaultSchedule;
            // ensure every day key exists
            foreach ($dayKeys as $d) {
                if (! isset($sched[$d]) || ! is_array($sched[$d])) {
                    $sched[$d] = ['closed'=>false, 'open'=>'10:00', 'close'=>'22:00'];
                } else {
                    $sched[$d] = [
                        'closed' => (bool) ($sched[$d]['closed'] ?? false),
                        'open'   => $sched[$d]['open']  ?? '10:00',
                        'close'  => $sched[$d]['close'] ?? '22:00',
                    ];
                }
            }
            return [
                '_key'            => 'b'.$b->id,
                'id'              => $b->id,
                'name'            => $b->name,
                'address'         => $b->address,
                'city'            => $b->city,
                'phone'           => $b->phone ?? '',
                'lat'             => $b->lat !== null ? (string) $b->lat : '',
                'lng'             => $b->lng !== null ? (string) $b->lng : '',
                'directions_url'  => $b->directions_url ?? '',
                'weekly_schedule' => $sched,
                'display_order'   => $b->display_order,
                'is_active'       => (bool) $b->is_active,
            ];
        })->values()->all()
        : [];
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.explore-partners.update', $partner) : route('admin.explore-partners.store') }}"
      enctype="multipart/form-data"
      id="explorePartnerForm"
      x-data="explorePartnerForm()"
      x-init="init(@js(['type' => old('type', $partner->type ?? 'online'), 'media' => $initialMedia, 'branches' => $initialBranches, 'defaultSchedule' => $defaultSchedule]))"
      class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ================= Basics ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-[#021c47]">Basics</h3>
            <p class="text-xs text-gray-500 mt-0.5">Name and type decide which grid this card appears in on the homepage.</p>
        </div>
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Partner name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $partner->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                @if($isEdit)
                    <p class="mt-1 text-xs text-gray-500">Slug: <code class="px-1 bg-gray-100 rounded">/{{ $partner->slug }}</code> (auto-updates if you rename)</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="online" x-model="type" class="sr-only peer">
                        <div class="px-4 py-2.5 border-2 border-gray-200 rounded-lg peer-checked:border-[#8BC34A] peer-checked:bg-[#F1F8E9] text-sm font-semibold text-center transition-colors">
                            <span class="text-[#558B2F]">🌐</span> Online
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="offline" x-model="type" class="sr-only peer">
                        <div class="px-4 py-2.5 border-2 border-gray-200 rounded-lg peer-checked:border-[#021c47] peer-checked:bg-[#EEF2F7] text-sm font-semibold text-center transition-colors">
                            <span class="text-[#021c47]">📍</span> Offline
                        </div>
                    </label>
                </div>
                @error('type')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="category_tag" class="block text-sm font-medium text-gray-700 mb-2">Category tag</label>
                <input type="text" id="category_tag" name="category_tag" value="{{ old('category_tag', $partner->category_tag) }}"
                       placeholder="Fashion &amp; Apparel"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                <p class="mt-1 text-xs text-gray-500">Shown above the name on the card. Free text — e.g., "Fashion &amp; Apparel", "Groceries", "Weekend Special".</p>
                @error('category_tag')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display order</label>
                <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $partner->display_order ?? 0) }}" min="0"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                <p class="mt-1 text-xs text-gray-500">Lower = appears first. Use this to control which partner shows top-left in the grid.</p>
            </div>

            <div class="lg:col-span-2">
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short description</label>
                <textarea id="short_description" name="short_description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                          placeholder="One or two sentences shown under the partner name on the card.">{{ old('short_description', $partner->short_description) }}</textarea>
                @error('short_description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Partner logo</label>
                <div class="flex items-start gap-4 flex-wrap">
                    <div class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($isEdit && $partner->logo_path)
                            <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="file" id="logo_file" name="logo_file" accept="image/*"
                               class="block text-sm w-full text-gray-600 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-[#021c47] file:text-white file:font-medium hover:file:bg-[#032a6b]">
                        <p class="mt-1.5 text-xs text-gray-500">PNG, JPG, SVG or WebP. Max 2 MB. Shown in the top-right chip on the card.</p>
                        @if($isEdit && $partner->logo_path)
                            <label class="mt-2 inline-flex items-center gap-2 text-xs text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                Remove current logo on save
                            </label>
                        @endif
                        @error('logo_file')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Visibility & chips ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-[#021c47]">Visibility &amp; chips</h3>
            <p class="text-xs text-gray-500 mt-0.5">Turn the partner on or off, and promote with the Featured / New chips shown on the card.</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:border-[#93db4d]/60 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $partner->is_active ?? true) ? 'checked' : '' }}
                       class="mt-0.5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]/30 w-4 h-4">
                <div>
                    <div class="text-sm font-semibold text-[#021c47]">Active</div>
                    <div class="text-xs text-gray-500">If off, this partner is hidden from the homepage but stays in the database.</div>
                </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:border-amber-300 transition-colors">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $partner->is_featured ?? false) ? 'checked' : '' }}
                       class="mt-0.5 rounded border-gray-300 text-amber-500 focus:ring-amber-500/30 w-4 h-4">
                <div>
                    <div class="text-sm font-semibold text-[#021c47]">Featured ⭐</div>
                    <div class="text-xs text-gray-500">Shows the amber "Featured" chip on the card. Manual on/off.</div>
                </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new', $partner->is_new ?? false) ? 'checked' : '' }}
                       class="mt-0.5 rounded border-gray-300 text-blue-500 focus:ring-blue-500/30 w-4 h-4">
                <div>
                    <div class="text-sm font-semibold text-[#021c47]">New ✨</div>
                    <div class="text-xs text-gray-500">Shows the blue "New" chip on the card. Manual on/off.</div>
                </div>
            </label>
        </div>
    </div>

    {{-- ================= Online CTA ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-show="type === 'online'" x-cloak>
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-[#021c47]">Online — Visit Store</h3>
            <p class="text-xs text-gray-500 mt-0.5">Destination URL for the "Visit Store" button on this card.</p>
        </div>
        <div class="p-6">
            <label for="visit_url" class="block text-sm font-medium text-gray-700 mb-2">Visit Store URL</label>
            <input type="url" id="visit_url" name="visit_url" value="{{ old('visit_url', $partner->visit_url) }}"
                   placeholder="https://example.com/"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
            @error('visit_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- ================= Offline header --- branch count label ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-show="type === 'offline'" x-cloak>
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-[#021c47]">Offline — headline info</h3>
            <p class="text-xs text-gray-500 mt-0.5">Shown in the info bar on the card, next to today's hours.</p>
        </div>
        <div class="p-6">
            <label for="branch_count_label" class="block text-sm font-medium text-gray-700 mb-2">Branch count label</label>
            <input type="text" id="branch_count_label" name="branch_count_label" value="{{ old('branch_count_label', $partner->branch_count_label) }}"
                   placeholder="42 branches"
                   class="w-full sm:max-w-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
            <p class="mt-1 text-xs text-gray-500">Free text — e.g., "42 branches", "68 outlets", "8 showrooms". Leave blank to auto-generate from branches added below.</p>
        </div>
    </div>

    {{-- ================= Media slides repeater (0-5) ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-[#021c47]">Media slides <span class="text-gray-400 font-normal text-sm">(0–5)</span></h3>
                <p class="text-xs text-gray-500 mt-0.5">Banner images and/or videos shown in the card's media area. Leave empty for a logo fallback.</p>
            </div>
            <button type="button" @click="addMedia()" x-show="media.length < 5"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#021c47] text-white text-sm font-semibold rounded-lg hover:bg-[#032a6b]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 4v16m8-8H4"/></svg>
                Add slide
            </button>
        </div>

        <div class="p-6 space-y-4">
            <template x-for="(row, i) in media" :key="row._key">
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/40">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-[#021c47]">Slide <span x-text="i + 1"></span></div>
                        <button type="button" @click="removeMedia(i)" class="text-xs text-red-600 hover:text-red-700 font-medium inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Remove
                        </button>
                    </div>

                    <input type="hidden" :name="`media[${i}][id]`" :value="row.id ?? ''">

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Kind</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" :name="`media[${i}][kind]`" value="image" x-model="row.kind" class="sr-only peer">
                                    <div class="px-3 py-1.5 border border-gray-200 rounded-lg peer-checked:border-[#8BC34A] peer-checked:bg-[#F1F8E9] text-xs font-semibold text-center">Image</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" :name="`media[${i}][kind]`" value="video" x-model="row.kind" class="sr-only peer">
                                    <div class="px-3 py-1.5 border border-gray-200 rounded-lg peer-checked:border-[#021c47] peer-checked:bg-[#EEF2F7] text-xs font-semibold text-center">Video</div>
                                </label>
                            </div>
                        </div>

                        {{-- Image mode --}}
                        <div class="lg:col-span-2" x-show="row.kind === 'image'">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Image file <span class="text-gray-400">(leave empty to keep current)</span></label>
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    <template x-if="row.image_url">
                                        <img :src="row.image_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!row.image_url">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </template>
                                </div>
                                <input type="file" :name="`media[${i}][image_file]`" accept="image/*"
                                       class="block text-xs w-full text-gray-600 file:mr-2 file:px-3 file:py-1.5 file:rounded-md file:border-0 file:bg-[#021c47] file:text-white file:font-medium hover:file:bg-[#032a6b]">
                            </div>
                        </div>

                        {{-- Video mode --}}
                        <div class="lg:col-span-2" x-show="row.kind === 'video'">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Video URL (YouTube or Vimeo)</label>
                            <input type="url" :name="`media[${i}][video_url]`" x-model="row.video_url"
                                   placeholder="https://www.youtube.com/watch?v=…"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>

                        <div class="lg:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Display order</label>
                            <input type="number" min="0" :name="`media[${i}][display_order]`" x-model="row.display_order"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>

                        <div class="lg:col-span-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Caption <span class="text-gray-400">(optional, 160 chars)</span></label>
                            <input type="text" maxlength="160" :name="`media[${i}][caption]`" x-model="row.caption"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>

                        <div class="lg:col-span-1 flex items-end">
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" value="1" :name="`media[${i}][is_active]`" x-model="row.is_active"
                                       class="rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]/30 w-4 h-4">
                                <span class="text-xs font-semibold text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="media.length === 0">
                <div class="text-center py-6 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    No slides yet — card will use the logo as a fallback. Click "Add slide" to add a banner or video.
                </div>
            </template>
        </div>
    </div>

    {{-- ================= Branches (offline only) ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-show="type === 'offline'" x-cloak>
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-[#021c47]">Branches</h3>
                <p class="text-xs text-gray-500 mt-0.5">Physical outlets shown in the drawer. First active branch drives the card's "Open / Closing soon" pill.</p>
            </div>
            <button type="button" @click="addBranch()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#021c47] text-white text-sm font-semibold rounded-lg hover:bg-[#032a6b]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 4v16m8-8H4"/></svg>
                Add branch
            </button>
        </div>

        <div class="p-6 space-y-5">
            <template x-for="(row, i) in branches" :key="row._key">
                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/40">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-semibold text-[#021c47]">Branch <span x-text="i + 1"></span> <span x-text="row.name ? '· ' + row.name : ''" class="text-gray-400 font-normal"></span></div>
                        <button type="button" @click="removeBranch(i)" class="text-xs text-red-600 hover:text-red-700 font-medium inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Remove
                        </button>
                    </div>

                    <input type="hidden" :name="`branches[${i}][id]`" :value="row.id ?? ''">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Branch name</label>
                            <input type="text" :name="`branches[${i}][name]`" x-model="row.name" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">City</label>
                            <input type="text" :name="`branches[${i}][city]`" x-model="row.city" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" :name="`branches[${i}][address]`" x-model="row.address" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Phone</label>
                            <input type="text" :name="`branches[${i}][phone]`" x-model="row.phone"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Display order</label>
                            <input type="number" min="0" :name="`branches[${i}][display_order]`" x-model="row.display_order"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Latitude</label>
                            <input type="number" step="0.0000001" :name="`branches[${i}][lat]`" x-model="row.lat"
                                   placeholder="33.7215"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Longitude</label>
                            <input type="number" step="0.0000001" :name="`branches[${i}][lng]`" x-model="row.lng"
                                   placeholder="73.0433"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Directions URL <span class="text-gray-400">(optional — leave blank to auto-generate from lat/lng)</span></label>
                            <input type="url" :name="`branches[${i}][directions_url]`" x-model="row.directions_url"
                                   placeholder="https://www.google.com/maps/dir/…"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 text-sm">
                        </div>
                    </div>

                    {{-- Weekly schedule --}}
                    <div class="mt-5 pt-4 border-t border-gray-200">
                        <div class="text-xs font-semibold text-[#021c47] uppercase tracking-wide mb-3">Weekly hours</div>
                        <div class="space-y-2">
                            @foreach($dayKeys as $day)
                                <div class="grid grid-cols-12 gap-2 items-center">
                                    <div class="col-span-12 sm:col-span-2 text-xs font-semibold text-gray-700 uppercase">{{ $dayLabels[$day] }}</div>
                                    <label class="col-span-4 sm:col-span-2 flex items-center gap-2 text-xs cursor-pointer">
                                        <input type="checkbox" value="1" :name="`branches[${i}][weekly_schedule][{{ $day }}][closed]`"
                                               x-model="row.weekly_schedule['{{ $day }}'].closed"
                                               class="rounded border-gray-300 text-red-500 focus:ring-red-500/30 w-4 h-4">
                                        <span class="text-gray-600">Closed</span>
                                    </label>
                                    <div class="col-span-4 sm:col-span-4">
                                        <input type="time" :name="`branches[${i}][weekly_schedule][{{ $day }}][open]`"
                                               x-model="row.weekly_schedule['{{ $day }}'].open"
                                               :disabled="row.weekly_schedule['{{ $day }}'].closed"
                                               class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs disabled:bg-gray-100 disabled:text-gray-400">
                                    </div>
                                    <div class="col-span-4 sm:col-span-4">
                                        <input type="time" :name="`branches[${i}][weekly_schedule][{{ $day }}][close]`"
                                               x-model="row.weekly_schedule['{{ $day }}'].close"
                                               :disabled="row.weekly_schedule['{{ $day }}'].closed"
                                               class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs disabled:bg-gray-100 disabled:text-gray-400">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Overnight is supported (e.g., 11:00 to 01:00 next day).</p>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input type="checkbox" value="1" :name="`branches[${i}][is_active]`" x-model="row.is_active"
                                   class="rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]/30 w-4 h-4">
                            <span class="text-xs font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </template>

            <template x-if="branches.length === 0">
                <div class="text-center py-6 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    No branches yet. Add at least one so customers can find the partner.
                </div>
            </template>
        </div>
    </div>

    {{-- ================= Save / Cancel ================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col sm:flex-row gap-3 sm:justify-end sticky bottom-4">
        <a href="{{ route('admin.explore-partners.index') }}"
           class="px-5 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center">Cancel</a>
        <button type="submit"
                class="px-6 py-2.5 bg-[#93db4d] text-[#021c47] font-bold rounded-lg hover:bg-[#7ec43f] shadow-sm">
            {{ $isEdit ? 'Save changes' : 'Create partner' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
function explorePartnerForm() {
    return {
        type: 'online',
        media: [],
        branches: [],
        _defaultSchedule: {},
        _counter: 0,

        init(initial) {
            this.type = initial.type || 'online';
            this.media = (initial.media || []).map(m => ({ ...m }));
            this.branches = (initial.branches || []).map(b => ({ ...b }));
            this._defaultSchedule = initial.defaultSchedule || {};
            this._counter = Date.now();
        },

        _key(prefix) {
            this._counter += 1;
            return prefix + this._counter;
        },

        addMedia() {
            if (this.media.length >= 5) return;
            this.media.push({
                _key: this._key('m-new-'),
                id: null,
                kind: 'image',
                image_url: null,
                image_path: null,
                video_url: '',
                caption: '',
                display_order: this.media.length,
                is_active: true,
            });
        },

        removeMedia(i) {
            this.media.splice(i, 1);
        },

        addBranch() {
            // Deep-clone the default schedule so each branch has its own copy
            const sched = {};
            Object.keys(this._defaultSchedule).forEach(d => {
                sched[d] = { ...this._defaultSchedule[d] };
            });
            this.branches.push({
                _key: this._key('b-new-'),
                id: null,
                name: '',
                address: '',
                city: '',
                phone: '',
                lat: '',
                lng: '',
                directions_url: '',
                weekly_schedule: sched,
                display_order: this.branches.length,
                is_active: true,
            });
        },

        removeBranch(i) {
            this.branches.splice(i, 1);
        },
    };
}
</script>
<style>[x-cloak]{display:none!important}</style>
@endpush
