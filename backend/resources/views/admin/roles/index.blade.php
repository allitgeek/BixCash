@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#021c47]">Roles & Permissions</h1>
            <p class="text-gray-500 mt-1">Manage user roles and their permissions</p>
        </div>
        @if(auth()->user()->hasPermission('roles.create'))
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Role
        </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <form id="filterForm" method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search roles..."
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
            </div>
            <select id="typeFilter" name="type" class="px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <option value="">All Types</option>
                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System Roles</option>
                <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom Roles</option>
            </select>
            <select id="statusFilter" name="status" class="px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Filter</button>
                <button type="button" id="clearFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Clear</button>
            </div>
        </form>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#021c47] text-white text-left">
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">Users</th>
                        <th class="px-4 py-3 font-semibold">Permissions</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody">
                    @include('admin.roles.partials.table-rows', ['roles' => $roles])
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            @if($roles->hasPages()) {{ $roles->appends(request()->query())->links() }} @endif
        </div>
    </div>
</div>

<div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
        <svg class="animate-spin h-8 w-8 text-[#021c47]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="text-gray-700 font-medium">Searching...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableBody = document.getElementById('rolesTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    let searchTimeout = null, currentRequest = null;

    function performSearch() {
        if (currentRequest) currentRequest.abort();
        loadingIndicator.classList.remove('hidden');
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (typeFilter.value) params.append('type', typeFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);

        currentRequest = new XMLHttpRequest();
        currentRequest.open('GET', '{{ route("admin.roles.search") }}?' + params.toString(), true);
        currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        currentRequest.onload = function() {
            loadingIndicator.classList.add('hidden');
            if (currentRequest.status === 200) {
                try {
                    const response = JSON.parse(currentRequest.responseText);
                    if (response.success) {
                        tableBody.innerHTML = response.tableHtml;
                        paginationContainer.innerHTML = response.paginationHtml || '';
                        window.history.pushState({}, '', '{{ route("admin.roles.index") }}' + (params.toString() ? '?' + params.toString() : ''));
                    }
                } catch (e) { alert('Error loading results.'); }
            }
            currentRequest = null;
        };
        currentRequest.onerror = function() { loadingIndicator.classList.add('hidden'); currentRequest = null; };
        currentRequest.send();
    }

    searchInput.addEventListener('input', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(performSearch, 500); });
    typeFilter.addEventListener('change', performSearch);
    statusFilter.addEventListener('change', performSearch);
    clearFiltersBtn.addEventListener('click', function() { searchInput.value = ''; typeFilter.value = ''; statusFilter.value = ''; performSearch(); });
});
</script>
@endsection
