@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Roles & Permissions</h1>
                <p class="mt-2 text-sm text-gray-600">Manage user roles and their permissions</p>
            </div>
            @if(auth()->user()->hasPermission('roles.create'))
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Role
            </a>
            @endif
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form id="filterForm" method="GET" action="{{ route('admin.roles.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search roles..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select id="typeFilter" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System Roles</option>
                        <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom Roles</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <button type="button" id="clearFilters" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Searching...</span>
        </div>
    </div>

    <!-- Roles List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody" class="bg-white divide-y divide-gray-200">
                    @include('admin.roles.partials.table-rows', ['roles' => $roles])
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
            @if($roles->hasPages())
                {{ $roles->appends(request()->query())->links() }}
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('error') }}');
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableBody = document.getElementById('rolesTableBody');
    const paginationContainer = document.getElementById('paginationContainer');

    let searchTimeout = null;
    let currentRequest = null;

    // AJAX search function
    function performSearch() {
        // Cancel previous request if still pending
        if (currentRequest) {
            currentRequest.abort();
        }

        // Show loading indicator
        loadingIndicator.classList.remove('hidden');

        // Build query string
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (typeFilter.value) params.append('type', typeFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);

        // Create AJAX request
        currentRequest = new XMLHttpRequest();
        currentRequest.open('GET', '{{ route("admin.roles.search") }}?' + params.toString(), true);
        currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        currentRequest.onload = function() {
            loadingIndicator.classList.add('hidden');

            if (currentRequest.status === 200) {
                try {
                    const response = JSON.parse(currentRequest.responseText);

                    if (response.success) {
                        // Update table body
                        tableBody.innerHTML = response.tableHtml;

                        // Update pagination
                        if (response.paginationHtml) {
                            paginationContainer.innerHTML = response.paginationHtml;
                        } else {
                            paginationContainer.innerHTML = '';
                        }

                        // Update URL without reloading page
                        const newUrl = '{{ route("admin.roles.index") }}' + (params.toString() ? '?' + params.toString() : '');
                        window.history.pushState({}, '', newUrl);
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Error loading results. Please try again.');
                }
            } else {
                alert('Error loading results. Please try again.');
            }

            currentRequest = null;
        };

        currentRequest.onerror = function() {
            loadingIndicator.classList.add('hidden');
            alert('Network error. Please check your connection.');
            currentRequest = null;
        };

        currentRequest.onabort = function() {
            loadingIndicator.classList.add('hidden');
            currentRequest = null;
        };

        currentRequest.send();
    }

    // Debounced search - AJAX call after 500ms of no typing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Instant AJAX on dropdown change
    typeFilter.addEventListener('change', performSearch);
    statusFilter.addEventListener('change', performSearch);

    // Clear all filters and search
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        typeFilter.value = '';
        statusFilter.value = '';
        performSearch();
    });

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('#paginationContainer a')) {
            e.preventDefault();
            const link = e.target.closest('a');
            const url = new URL(link.href);
            const page = url.searchParams.get('page');

            if (page) {
                // Add page to current filters
                const params = new URLSearchParams();
                if (searchInput.value) params.append('search', searchInput.value);
                if (typeFilter.value) params.append('type', typeFilter.value);
                if (statusFilter.value) params.append('status', statusFilter.value);
                params.append('page', page);

                // Show loading
                loadingIndicator.classList.remove('hidden');

                // Fetch page via AJAX
                fetch('{{ route("admin.roles.search") }}?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingIndicator.classList.add('hidden');

                    if (data.success) {
                        tableBody.innerHTML = data.tableHtml;
                        if (data.paginationHtml) {
                            paginationContainer.innerHTML = data.paginationHtml;
                        } else {
                            paginationContainer.innerHTML = '';
                        }

                        // Update URL
                        const newUrl = '{{ route("admin.roles.index") }}?' + params.toString();
                        window.history.pushState({}, '', newUrl);

                        // Scroll to top of table
                        document.querySelector('.bg-white.rounded-lg.shadow').scrollIntoView({ behavior: 'smooth' });
                    }
                })
                .catch(error => {
                    loadingIndicator.classList.add('hidden');
                    console.error('Error:', error);
                    alert('Error loading page. Please try again.');
                });
            }
        }
    });
});
</script>
@endsection
