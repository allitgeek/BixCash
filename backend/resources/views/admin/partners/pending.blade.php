@extends('layouts.admin')

@section('title', 'Pending Partner Applications - BixCash Admin')
@section('page-title', 'Pending Partner Applications')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Partner Applications</h3>
            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">View All Partners</a>
        </div>
        <div class="card-body">

            @if($applications->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Partner</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Business Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Phone</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Business Type</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Applied</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $partner)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $partner->name }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $partner->partnerProfile->business_name ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $partner->phone }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $partner->partnerProfile->business_type ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $partner->created_at->format('M j, Y') }}
                                            <br>
                                            <span style="color: #999;">{{ $partner->created_at->diffForHumans() }}</span>
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.partners.show', $partner) }}"
                                               class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Review Application
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $applications->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No pending applications</h4>
                    <p>All partner applications have been reviewed.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
