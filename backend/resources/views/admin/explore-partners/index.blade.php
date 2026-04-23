@extends('layouts.admin')

@section('title', 'Explore Partners - BixCash Admin')
@section('page-title', 'Explore Partners')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-[#021c47]">Homepage partner cards</h3>
                <p class="text-sm text-gray-500 mt-1">Manage the Online &amp; Offline partners shown in the "Explore Partners" section on the public homepage.</p>
            </div>
            <a href="{{ route('admin.explore-partners.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#93db4d] text-[#021c47] font-semibold rounded-lg hover:bg-[#7ec43f] transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Partner
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.explore-partners.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name, category, description…"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
            </div>
            <select name="type" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <option value="">All types</option>
                <option value="online" @selected(request('type') === 'online')>Online</option>
                <option value="offline" @selected(request('type') === 'offline')>Offline</option>
            </select>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <option value="">Any status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#032a6b] transition-colors">Filter</button>
                @if(request()->anyFilled(['search','type','status','featured']))
                    <a href="{{ route('admin.explore-partners.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if($partners->count() === 0)
            <div class="p-12 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <h4 class="text-lg font-semibold text-[#021c47]">No partners yet</h4>
                <p class="text-sm text-gray-500 mt-1 mb-4">Add your first Online or Offline partner to populate the homepage section.</p>
                <a href="{{ route('admin.explore-partners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#93db4d] text-[#021c47] font-semibold rounded-lg hover:bg-[#7ec43f]">
                    Add Partner
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Partner</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Media</th>
                            <th class="px-4 py-3 text-center">Featured</th>
                            <th class="px-4 py-3 text-center">Active</th>
                            <th class="px-4 py-3 text-center">Order</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($partners as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($p->logo_path)
                                                <img src="{{ asset('storage/'.$p->logo_path) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-[10px] font-bold text-gray-400">{{ Str::upper(Str::substr($p->name, 0, 3)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-[#021c47]">{{ $p->name }}</div>
                                            <div class="text-xs text-gray-400">/{{ $p->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($p->type === 'online')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-[#558B2F] text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#8BC34A]"></span> Online
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 text-[#021c47] text-xs font-semibold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            Offline
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $p->category_tag ?: '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    <span class="text-xs">{{ $p->media->count() }} slide{{ $p->media->count() === 1 ? '' : 's' }}</span>
                                    @if($p->type === 'offline')
                                        <span class="mx-1 text-gray-300">·</span>
                                        <span class="text-xs">{{ $p->branches->count() }} branch{{ $p->branches->count() === 1 ? '' : 'es' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" data-toggle-featured="{{ $p->id }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $p->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400 hover:text-gray-600' }} transition-colors"
                                            title="Toggle featured">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.2H22l-6 4.4 2.3 7.2L12 16.4 5.7 20.8 8 13.6 2 9.2h7.6L12 2z"/></svg>
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" data-toggle-status="{{ $p->id }}"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full {{ $p->is_active ? 'bg-[#93db4d]' : 'bg-gray-300' }} transition-colors"
                                            title="Toggle active">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform {{ $p->is_active ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $p->display_order }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.explore-partners.edit', $p) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[#021c47] hover:bg-gray-100"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.explore-partners.destroy', $p) }}" method="POST"
                                              onsubmit="return confirm('Delete this partner and all its media/branches? This cannot be undone.');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    async function toggle(url) {
        const res = await fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });
        if (!res.ok) {
            alert('Failed. Please retry.');
            return;
        }
        location.reload();
    }

    document.querySelectorAll('[data-toggle-status]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle-status');
            toggle(`/admin/explore-partners/${id}/toggle-status`);
        });
    });

    document.querySelectorAll('[data-toggle-featured]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle-featured');
            toggle(`/admin/explore-partners/${id}/toggle-featured`);
        });
    });
})();
</script>
@endpush
@endsection
