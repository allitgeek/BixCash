@extends('layouts.admin')

@section('title', 'Customer Queries - BixCash Admin')
@section('page-title', 'Customer Queries')

@section('content')
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $stats['total'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Total Queries</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $stats['new'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">New Queries</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $stats['in_progress'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">In Progress</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $stats['resolved'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Resolved</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Customer Queries</h3>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Email"
                               style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                        <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="">All Statuses</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Filter</button>
                        <a href="{{ route('admin.queries.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1.5rem;">Clear</a>
                    </div>
                </div>
            </form>

            @if($queries->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Email</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Message</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Submitted</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($queries as $query)
                                <tr style="border-bottom: 1px solid #dee2e6; {{ $query->read_at ? '' : 'background: #fff3cd;' }}">
                                    <td style="padding: 0.75rem;">
                                        @if($query->status == 'new')
                                            <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                New
                                            </span>
                                        @elseif($query->status == 'in_progress')
                                            <span style="background: #f39c12; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                In Progress
                                            </span>
                                        @elseif($query->status == 'resolved')
                                            <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Resolved
                                            </span>
                                        @else
                                            <span style="background: #95a5a6; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Closed
                                            </span>
                                        @endif
                                        @if(!$query->read_at)
                                            <span style="display: block; margin-top: 0.25rem; font-size: 0.7rem; color: #856404;">Unread</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $query->name }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $query->email }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">{{ Str::limit($query->message, 80) }}</small>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $query->created_at->format('M j, Y') }}<br>
                                            {{ $query->created_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="{{ route('admin.queries.show', $query) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                View
                                            </a>
                                            <form method="POST" action="{{ route('admin.queries.destroy', $query) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this query?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $queries->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No queries found</h4>
                    <p>When customers submit queries through the contact form, they will appear here.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple pagination styling
    const paginationLinks = document.querySelectorAll('.pagination a, .pagination span');
    paginationLinks.forEach(link => {
        link.style.cssText = 'padding: 0.5rem 0.75rem; margin: 0 0.25rem; border: 1px solid #dee2e6; border-radius: 3px; text-decoration: none; color: #495057;';
        if (link.classList.contains('active')) {
            link.style.cssText += 'background: #3498db; color: white; border-color: #3498db;';
        }
    });
});
</script>
@endpush
